<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Membership extends Model
{


    public static function getCurrentMembership($user_id){

    	//Get memberships
        $user = user::with('membership')->with('man')->find($user_id);

        if(!isset($user->membership[0]))
        	return false;

        //Remove out off date memberships
        $memberships = [];
        $i = 0;
        foreach ($user->membership as $membership) {
        	$endDate = $membership->pivot->created_at->timestamp + $membership->period * 24 * 60 * 60;

        	if ($endDate > now()->timestamp){
        		$memberships[$i] = $membership;
        		$memberships[$i]['endDate'] = $endDate;
        		$i++;
        	}        	
        }

        if(!count($memberships)) return false;

        //Find profitable membership
        // profitable
        $minLetterPrice = 9999;
        foreach ($memberships as $membership){
        	if($membership->letter_price < $minLetterPrice) $minLetterPrice = $membership->letter_price;
        }

        //Remove not profitable memberships
        foreach ($memberships as $k => $membership){
        	if($membership->letter_price > $minLetterPrice) unset($memberships[$k]);
        }

        if(!count($memberships)) return false;

        //Get longest end date membership
        $latestDate = [
        	'latestDate' => 0,
        	'k' => 0,
        ];
        foreach ($memberships as $k => $membership){
        	if($membership->endDate > $latestDate['latestDate']){
        		$latestDate['latestDate'] = $membership->endDate;
        		$latestDate['k'] = $k;
        	}
        }
       
       $CurrentMembership = $memberships[$latestDate['k']];
       $CurrentMembership->balance = $user->man->balance;

       return $CurrentMembership;
    }

    public static function getChatPrice($userId){
        $membership = Membership::getCurrentMembership($userId);
        return $membership->chat_price / 60;
    }

    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
