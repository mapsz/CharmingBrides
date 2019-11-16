<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Girl;
use App\Room;
use App\Message;
use Auth;
use App\Events\ChatInvite;
use App\Events\PrivateChat;
use App\Events\ChatNotification;
use App\Events\Chat;
use App\ChatHardOnline;
use App\ChatHistory;
use Illuminate\Support\Facades\Log;
use App\Rules\CurrentUserOrAdmin;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.chat')->with('user', json_encode(User::getWithInfo(Auth::id())));
    }

    public function chatInvite(Request $request){

      //Validation
      if(!isset($request->from) || !isset($request->to) || !isset($request->status)){
        return response()->json(['error' => '1']);
      }            
      $request->validate([
        'from'=> new CurrentUserOrAdmin
      ]);

      //Invite
      Chat::dispatch([
        'type'    => 'invite',
        'from'    => $request->from,
        'to'      => $request->to,
        'status'  => $request->status
      ]);

      return response()->json(['error' => '0']);

    }

    public function roomRead(Request $request){

      if(!isset($request->room_id) || !isset($request->user_id)){
        return response()->json(['error' => '1']);
      }      

      //Validation
      $request->validate([
        'user_id'=> new CurrentUserOrAdmin
      ]);

      ChatHistory::roomRead($request->room_id,$request->user_id);

      return response()->json(['error' => '0']);
    }

    public function startHistory(Request $request){

      if(!isset($request->room) || !isset($request->session)){
        return response()->json(['error' => '1', 'text' => 'Data error!']);
      }

      $id = ChatHistory::startHistory($request->room,$request->session);

      if(!$id){
        return response()->json(['error' => '1', 'text' => 'Connect error!']);
      }

      return response()->json(['error' => '0', 'data' => $id]);
    }

    public function stopChat(Request $request){
      if(!isset($request->history)){
        return response()->json(['error' => '1', 'text' => 'Data error!']);
      }

      $id = ChatHistory::stopChat(0,0,$request->history);

      return response()->json(['error' => '0']);
    }

    public function payChat(Request $request){
      if(!isset($request->history)){
        return response()->json(['error' => '0']);
      }

      $id = ChatHistory::payChat(0,0,$request->history);

      return response()->json(['error' => '0']);
    }    

    public function getRoom(Request $request){

        // Get user id
        $userId = $this->getUser($request->userId);
        if(!$userId) return response()->json(['error' => '1', 'text' => 'No user id']);


        //Get companion
        $companionId = $request->companionId;
        if(!$companionId) return response()->json(['error' => '1', 'text' => 'No companion id']);

        //Check user is admin
        if(Auth::user()->role > 2 && Auth::user()->id == $userId){
            return response()->json(['error' => '1', 'text' => 'Admin cant chat, Please choose girl!']);
        }

        //Check user leged in
        if(Auth::user()->id != $userId && Auth::user()->role < 3){
            return response()->json(['error' => '1', 'text' => 'Bad login']);
        }

        // Check user not companion
        if($userId == $companionId) return response()->json(['error' => '1', 'text' => 'Cant chat with self']);
      
        // Check if different genders
        $currentUser = User::getWithInfo($userId);
        $companion   = User::getWithInfo($companionId);
        if($currentUser['man'] == $companion['man']){
            return response()->json(['error' => '1', 'text' => 'Cant chat with same gender']);
        }        

        // Get user and companion rooms
        $room = $this->searchRoom($userId, $companionId);
        if(!$room) {
            //Create room
            $room = $this->storeRoom($userId,$companionId);
        }

        //Add companion
        $room['companion'] = $companion;
        if(!$room['companion']){
            return response()->json(['error' => '1', 'text' => 'Error geting companion']);
        }

        $r = [
            'companion' => $companion,
            'id'        => $room['id'],
        ];

        return $r;
    }

    public function storeRoom($userId,$companionId){

        // Create room
        $room = User::find($userId)->room()->create();
 
        //Attach companion
        $companion = User::find($companionId);
        $companion->room()->attach($room->id);
        $companion->save();

        return $room;
    }

    private function searchRoom($userId1, $userId2){
        // Get users rooms
        $user1Rooms = User::with('room')->find($userId1);
        $user2Rooms = User::with('room')->find($userId2);

        // Return if some was no rooms
        if(!$user1Rooms || !$user2Rooms) return false;

        // Search same room
        $room = false;
        foreach($user1Rooms->room as $user1Room){
            foreach($user2Rooms->room as $user2Room){
                if($user1Room->id == $user2Room->id){
                    // Found !!
                    return $user1Room;
                }
            }
        }

        return false;
    }

    private function getUser($userId){
        // Get user id
        if(!Auth::user()->role > 2){ // If not admin
            $userId = Auth::user()->id;
        }

        if(!$userId)    return false;
        else            return $userId;
    }

    public function storeMessage(Request $request){
      // Get user id
      $userId = $this->getUser($request->userId);
      if(!$userId) return response()->json(['error' => '1', 'text' => 'No user id']);
      //Get room
      $roomId = $request->roomId;
      if(!$roomId) return response()->json(['error' => '1', 'text' => 'No room id']);
      //Get body
      $body = $request->body;
      if(!$body) return response()->json(['error' => '1', 'text' => 'No body']);
      //Get session
      $session = $request->session;
      if(!$session) return response()->json(['error' => '1', 'text' => 'No session']);

       //Check user is admin
      if(Auth::user()->role > 2 && Auth::user()->id == $userId){
          return response()->json(['error' => '1', 'text' => 'Admin cant chat, Please choose girl!']);
      }

      //Check user belong to room
      if(!$this->userBelongToRoom($userId, $roomId))
          return response()->json(['error' => '1', 'text' => 'User doesnt belong to this room']);

      //Prepare message
      $message = New Message;
      $message->user_id = $userId;
      $message->room_id = $roomId;
      $message->body = $body;

      //Man busines
      $user = User::getWithInfo(Auth::user()->id);
      if($user['man'] === 1){

        if($user['balance'] == 0){
          response()->json(['error' => '1', 'text' => 'Low balance!']);
        }

        if(!$request->history) return response()->json(['error' => '1', 'text' => 'Somethink gone wrong', 'code' => 1]);

        //Pay chat
        if(!ChatHistory::payChat($roomId, $session,$request->history)){
          return response()->json(['error' => 1, 'text' => 'Somethink gone wrong', 'code' => 2]);
        }       
      }

      //Store message
      if(!$message->save()){
        return response()->json(['error' => 1, 'text' => 'Somethink gone wrong', 'code' => 3]);
      }

      //Get to user
      $roomUsers = Room::where('id',$roomId)->with('user')->first()->user;

      foreach ($roomUsers as $key => $v) {
        if($v->id != $userId){
          $toUserId = $v->id;
          break;
        } 
      }

      //Trigger event
      PrivateChat::dispatch($roomId, $message);
      //Notification
      Chat::dispatch([
        'type' => 'message',
        'from' => $userId,
        'to' =>   $toUserId,
        'room' => $roomId
      ]);

      //return
      return response()->json(['error' => 0]);
    }

    public function getMessages(Request $request){

        // Get user id
        $userId = $this->getUser($request->userId);
        if(!$userId) return response()->json(['error' => '1', 'text' => 'No user id']);

        //Get room
        $roomId = $request->roomId;
        if(!$roomId) return response()->json(['error' => '1', 'text' => 'No room id']);

        //Check user belong to room
        if(!$this->userBelongToRoom($userId, $roomId))
            return response()->json(['error' => '1', 'text' => 'User doesnt belong to this room']);

        //Get message
        $messages = Message::where('room_id', $roomId)->get();

        return response()->json(['error' => false, 'messages' => $messages]);
    }

    private function userBelongToRoom($userId, $roomId){
        // Get room
        $room = Room::with('user')->find($roomId);
        // Check if user belong
        if($room == null) 
            return false;
        if(!$room->user->contains($userId))
            return false;

        return true;
    }

    public function getRecentRooms(Request $request){

        //Validation
        $request->validate([
          'user_id'=> new CurrentUserOrAdmin
        ]);

        $userId = $request->user_id;

        //Get rooms
        $user = User::
          with(['room' => function($q)use($userId){
            $q->has('message')
            ->with(['user' => function($q3)use($userId){
              $q3->where('id','<>',$userId)->with('girl')->with('man');
            }])
            ->with(['message' => function($q2){
              $q2->orderBy('updated_at', 'DESC');
            }]);
          }])->find($userId);

        $rooms = $user['room'];

        //Formate rooms 
        $fRoom = [];
        foreach ($rooms as $k => $v) {
          $fr['id'] = $v['id'];
          $fr['lastActivity'] = $v['message'][0]['updated_at']->timestamp;
          $fr['read'] = $v['message'][0]['updated_at'] < $v['pivot']['read'];
          $fr['companion'] = User::getWithInfo($v['user'][0]['id'],$v['user'][0]);
          array_push($fRoom, $fr);
        }
  
        //Sort
        usort($fRoom, function($a, $b) {
          return $b['lastActivity'] <=> $a['lastActivity'];
        });

        return response()->json(['error' => false, 'data' => $fRoom]);
    }

    public function getHardOnline(){

        $admin_id = auth::user()->id;

        $users = ChatHardOnline::where('admin_id', '=', $admin_id)->get();

        if(count($users) == 0) return [];

        //Get info
        //set where
        $where = [];
        foreach ($users as $key => $user) {
          $u = ['column' => 'user_id','condition' => '=','value' => $user->user_id,'or' => true];
          array_push($where, $u);
        }

        $model = new Girl();        
        $columns = [
          [
            'name'    => 'photo',
            'file'    => 'image',
          ],
          ['name' => 'id'],
          ['name' => 'name'],
          ['name' => 'birth'],
        ];
        $model->setColumns($columns);

        $model->setWhere($where);
        $data = $model->getData();

        return $data['data'];
    } 

    public function setHardOnline(Request $request){

        $user = User::getWithInfo($request->id);
        $adminId = auth::user()->id;

        if($user['man']){
            return response()->json(['error' => '1', 'text' => 'Cant control mans']);
        }

        //Check if already onlined
        $online = ChatHardOnline::where([['user_id','=',$user['id']],['admin_id','=',$adminId]])->get();

        if(isset($online[0])){
            return response()->json(['error' => '1', 'text' => 'already hard onlined']);
        }

        $hardOnline = new ChatHardOnline();
        $hardOnline->user_id = $user['id'];
        $hardOnline->admin_id = $adminId;

        if($hardOnline->save()){
            return response()->json(['error' => false, 'text' => 'ok']);
        }else{
            return response()->json(['error' => '1', 'text' => 'Somethink gone wrong']);
        }
    }

    public function deleteHardOnline(Request $request){

        //@@@
        $userId  = $request->id;
        $adminId = auth::user()->id;

        $online = ChatHardOnline::where([['user_id','=',$userId],['admin_id','=',$adminId]])->delete();

        return response()->json(['error' => false, 'text' => 'ok']);
    }

    public function searchGirl(Request $request){

      $role = Auth::User()->role;

      if($role < 3){
        return false;
      }
      $search  = $request->search;

      //Get Model
      $model = new Girl;

      $model->setInfoColumns();
      //custom
      $custom = $model;
      if(isset($search->search)){
        $val = $search->search;      
        $custom = $custom->where(function($q)use($val) {
          $q->where('name','LIKE','%'.$val.'%')
            ->orWhere('id','LIKE','%'.$val.'%');
        });
      }


      if($role == 3){
        $id = Auth::User()->agent->id;
        $callback = function($q)use($id) {
          $q->where('id','=',$id);
        };

        $callback = function($q)use($id) {
          $q->where('id','=',$id);
        };              
        $custom = $custom->whereHas('agent' , $callback)->with(['agent' => $callback]);
        $model->setCustomQueries($custom);
      }

      $model->setCustomQueries($custom);
    
      //Get Data
      $data   = $model->getData();       

      return response()->json(['error' => '0', 'data' => $data]);

    }

}
