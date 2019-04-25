<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ChatHistory;
use Carbon\Carbon;
use App\User;

class ChatHistoryController extends Controller
{
    public function index()
    {

    	//Get histories from DB
        $chatHistories = ChatHistory::with('room.user')->get()->sortByDesc('stop_at');

        
        $r = [];
        foreach($chatHistories as $c){

        	//Get users
          	foreach ($c['room']['user'] as $user) { //@@@ нагрузка??!
          		$u = User::getWithInfo($user->id);
          		if($u['man']){
          			$man = $u;
          		}else{
					$girl = $u;
          		}          		
          	}

          	//Get timestamp
        	$stop = new Carbon($c['stop_at']);
        	$stop = $stop->timestamp;
        	array_push($r, [
        		'id' 		=> $c['id'],
        		'roomId'	=> $c['room']['id'],
        		'start'		=> $c['created_at']->timestamp,
        		'stop'		=> $stop,
        		'price'		=> $c['price'],
        		'man'		=> $man,
        		'girl'		=> $girl,
          	]);
        }
        

        return view('admin.pages.chatHistory')->with('chatHistories',json_encode($r));

    }
}
