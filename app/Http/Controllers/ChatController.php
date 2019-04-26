<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Room;
use App\Message;
use Auth;
use App\Events\ChatInvite;
use App\Events\PrivateChat;
use App\Events\Chat;
use App\ChatHardOnline;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        // $this->getRecentRooms();

            // $user = User::getWithInfo(10);
            // Chat::dispatch($user);



            // return 77;

        return view('pages.chat')->with('user', json_encode(User::getWithInfo(Auth::id())));
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

    // public function intviteCompanion(Request $request){

    //     // Get user id
    //     $userId = $this->getUser($request->userId);
    //     if(!$userId) return response()->json(['error' => '1', 'text' => 'No user id']);

    //     //Get room
    //     $roomId = $request->roomId;
    //     if(!$roomId) return response()->json(['error' => '1', 'text' => 'No room id']);

    //      //Check user is admin
    //     if(Auth::user()->role > 2 && Auth::user()->id == $userId){
    //         return response()->json(['error' => '1', 'text' => 'Admin cant invite']);
    //     }

    //     //Check user belong to room
    //     if(!$this->userBelongToRoom($userId, $roomId))
    //         return response()->json(['error' => '1', 'text' => 'User doesnt belong to this room']);      
    //     //Get user info 
    //     $user = User::getWithInfo($userId);

    //     //Get room
    //     $room = Room::find($roomId);

    //     //Update invate
    //     if($user['man'])  $room->man_confirm  = 1; // Man
    //     else              $room->girl_confirm = 1; // Girl
        
    //     //Save invite
    //     $room->save();

    //     //Run event //@@@
    //     // ChatInvite::dispatch($roomId);

    //     return $room;
    // }

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

         //Check user is admin
        if(Auth::user()->role > 2 && Auth::user()->id == $userId){
            return response()->json(['error' => '1', 'text' => 'Admin cant chat, Please choose girl!']);
        }

        //Check user belong to room
        if(!$this->userBelongToRoom($userId, $roomId))
            return response()->json(['error' => '1', 'text' => 'User doesnt belong to this room']);

        //Store message
        $message = New Message;
        $message->user_id = $userId;
        $message->room_id = $roomId;
        $message->body = $body;
        $message->save();

        //Trigger event
        PrivateChat::dispatch($roomId, $message);

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

    public function getRecentRooms(){

        //Current user id
        $userId = Auth::user()->id;

        //Get rooms list
        // $rooms = Room::with('user')->get();

        $rooms = Room::whereHas('user', function ($query) use($userId) {
                $query->where('id', '=', $userId);
            })->with(['message' => function ($query) {
                $query->orderBy('updated_at', 'DESC
                    ');
            }])->get();



        //Set last activities
        foreach ($rooms as $k => $room) {
            if (isset($room->message[0])){
                //Last message
                $rooms[$k]['lastActivity'] = $room->message[0]->updated_at;
            }else{
                //Room update if no message
                $rooms[$k]['lastActivity'] = $room->updated_at;
            }            
        }
        //Sort by actovities
        $rooms = $rooms->sortBy('lastActivity')->reverse();
  


        //Form return array
        $r = [];
        $i = 0;
        foreach ($rooms as $k => $room) {
             $r[$i]['id'] = $room->id;
             $r[$i]['lastActivity'] = $room['lastActivity']->timestamp;

             // Add companion
             foreach ($room->user as $l => $user) {
                if($user['id'] != $userId){
                    $r[$i]['companion'] = User::getWithInfo($user['id']);
                    break;               
                }
            }

            $i++;

        }     

        return response()->json(['error' => false, 'rooms' => $r]);
    }

    public function getHardOnline(){

        $admin_id = auth::user()->id;

        $users = ChatHardOnline::where('admin_id', '=', $admin_id)->get();

        //Get info
        $r = [];
        foreach ($users as $key => $user) {
            $u = user::getWithInfo($user->user_id);
            array_push($r, $u);
        }

        return $r;
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

}
