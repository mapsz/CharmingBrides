<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Room;
use Storage;
use App\User;
use Auth;
// ChatHistory::chatDisconnect($this->channelName, $this); //###


class ChatHistory extends Model
{
	protected $guarded = [ ];

    public static function roomRead($roomId,$userId){
      //Get room
      $r = User::with(['room' => function($q)use($roomId){$q->find($roomId);}])->find($userId);

      //Set time
      $r->room->first()->pivot->read = Carbon::now();

      //Update
      $r->room->first()->pivot->save();
    }

    public static function payChat($roomId, $session,$history = false){
      //Get history
      if(!$history)
        $history = self::findHistory($roomId, $session);

      if(gettype($history) != 'object'){
        $history = ChatHistory::find($history);
      }

      if(!$history) return false;     

      //Get last pay
      $last_pay = ($history->last_pay ? $history->last_pay : $history->created_at) ;
      $last_pay = Carbon::parse($last_pay);

      //Get current time
      $currentTime = Carbon::now();

      //Get difference
      $diffTime = $last_pay->diffInSeconds($currentTime);

      //Exit if already payed
      if($last_pay > $currentTime) return true;

      //Get man
      $room = Room::with('user')->with('user.man')->find($history->room_id);
      $user  = $room->user[0]->man ? $room->user[0] : ($room->user[1]->man ? $room->user[1] : false);

      //Get chat price
      $membership = Membership::getCurrentMembership($user->id);
      $chatPrice = $membership->chat_price;
      //cost per socond
      $costPerSec = $chatPrice / 60;
      //pay summ
      $paySumm = round($diffTime * $costPerSec,2);

      //Edit balance 
      $user->man->balance -= $paySumm;
      if($user->man->balance < 0) $user->man->balance = 0;
      if(!$user->man->save()) return false;

      //Update last pay
      $history->last_pay = $currentTime;
      $history->price += $paySumm;
      if(!$history->save()) return false;

      return $paySumm;
    }

    public static function stopChat($roomId, $session,$history){
      //Get history
      if(!$history)
        $history = self::findHistory($roomId, $session);

      if(gettype($history) != 'object'){
        $history = ChatHistory::find($history);
      }

      if(!$history) return false;        

      //Pay chat
      self::payChat($history->room_id, $session,$history);

      //Set stop
      $history->stop_at = Carbon::now();
      if(!$history->save()) return false;

      return true;
    }

    protected static function findHistory($roomId, $session){

      return ChatHistory::where('room_id',$roomId)
                        ->where('session',$session)
                        ->where('stop_at',null)
                        ->orderBy('created_at','desc')
                        ->first();
    }

    //
    public static function chatDisconnect($room, $connection){

    	//Check if private room
    	if(!strpos($room, 'privateChat')){
    		return false;
    	}

        //Get leavead user
        $users      = $connection->getUsers();
        $connection = $connection->getSubscribedConnections();

        //Remove online users
        foreach ($connection as $key => $value) {
            unset($users[$key]);
        }

        //Check if leaved user is man
        foreach ($users as $key => $value) {
            if($value->user_info->man !== true){
                return false;
            } 
        }

    	
        //@@@
        echo '@@@@@@@';

      //Get room id
    	$roomId = intval(explode('.',$room)[1]);

    	//Get start/stop
    	$stop_at 	= Carbon::now(); //Stop
    	$history 	= ChatHistory::where('room_id', '=', $roomId)->where('stop_at', '=', NULL)->first();
    	if($history === null) return false;
    	$start_at 	= $history->created_at;
    	$historyId 	= $history->id;

    	//Get man from room
    	$userId = Room::getManFromRoom($roomId);


    	//Update balance
    	$amount = User::payChat($userId, $stop_at->timestamp - $start_at->timestamp);

    	//Update history
    	$history->update(['stop_at' => $stop_at, 'price' => $amount]);
    }

    public static function startHistory($room, $session){
    
      $user = user::with('man')->find(Auth::id());

      //Chack if man
      if(!$user->man){
        return true;
      }

      //Get membership
      $membership = Membership::getCurrentMembership($user->id);

      //Check balance
      if($membership->chat_price > $user->man->balance) return false;

      //Update balance
      $user->man->balance -= $membership->chat_price;
      if(!$user->man->save())return false;

      //Create history
      $history = new ChatHistory();

      $history->room_id = $room;
      $history->session = $session;
      $history->price = $membership->chat_price;
      $history->last_pay = Carbon::now()->addMinutes(1);

      if(!$history->save())return false;
  
      return $history->id;
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }
}
