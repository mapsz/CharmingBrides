<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Room extends Model
{
    //
    public static function getManFromRoom($roomId){
    	//Get room users
    	$room = Room::with('user')->find($roomId);

    	//Find man
    	foreach ($room->user as $key => $user) {
    		$userInfo = User::getWithInfo($user->id);
    		if($userInfo['man'])
    			return $userInfo['id'];
    	}

    	return false;
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
    public function message()
    {
        return $this->hasMany('App\Message');
    }   
}
