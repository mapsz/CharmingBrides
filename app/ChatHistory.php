<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Room;
use Storage;

class ChatHistory extends Model
{
	protected $guarded = [ ];
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

    public function room()
    {
        return $this->belongsTo('App\Room');
    }
}
