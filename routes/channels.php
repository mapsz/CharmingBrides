<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

use app\User;
use Illuminate\Support\Facades\Auth;
use App\ChatHistory;
use App\Room;
use App\Membership;
// use Illuminate\Support\Collection;

Broadcast::channel('common_room', function ($user = null) {
    return ['id' => str_random(16)];
});


// //Online
// Broadcast::channel('chat', function ($authtUser) {

//   return [33];

// 	//Check user auth
// 	if(Auth::user()->id != $authtUser->id && !Auth::user()->role > 2) return false;

// 	// Get user info
// 	$authtUser = User::getWithInfo($authtUser->id);

// 	// Check if admin
// 	if($authtUser['man'] > 2){
// 		// Get hard online
// 		$hardOnlines = User::getHardOnline();
// 		if(isset($hardOnlines[0])){
// 			// Get hard online users
// 			$user = [];
// 			foreach ($hardOnlines as $hardOnline) {
// 				array_push($user, User::getWithInfo($hardOnline->user_id));
// 			}
// 			return $user;
// 		}else{
// 			// No hard online
// 			return true;
// 		}	
// 	}else{
// 		//User log in
// 		return $authtUser;
// 	}
// });

// Broadcast::channel('privateChat.{roomId}', function ($user, $roomId) {

// 	//Check user auth
// 	if(Auth::user()->id != $user->id && !Auth::user()->role > 2) return false;

// 	if(Auth::user()->role > 2) return $user;

// 	//Check correct room
//   $room = Room::with('user')->get()->find($roomId);
//   $exit = true;
//   foreach ($room['user'] as $key => $v) {
//        if($v->id == $user->id){
//        	$exit = false;
//        	break;
//        }
//   }
// 	if($exit) return false;

// 	// Add Chat history if man
// 	// $user = User::getWithInfo($user->id);
// 	// if($user['man'] === 1){
// 	// 	if (Membership::getCurrentMembership($user['id'])->chat_price > $user['balance']) {
//  //      return false;
//  //    }      
//  // 	}

// 	return $user;
// });

// Broadcast::channel('chatNotification.{userId}', function ($user, $userId) {

// 	if(Auth::user()->id != $user->id && !Auth::user()->role > 2) return false;

// 	return $user;

// });
