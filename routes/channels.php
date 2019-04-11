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
use App\ChatHistory;
// use Illuminate\Support\Collection;

//Online
Broadcast::channel('chat', function ($currentUser) {

	// Get user info
	$currentUser = User::getWithInfo($currentUser->id);

	// Check if admin
	if($currentUser['man'] > 2){
		// Get hard online
		$hardOnlines = User::getHardOnline();
		if(isset($hardOnlines[0])){
			// Get hard online users
			$user = [];
			foreach ($hardOnlines as $hardOnline) {
				array_push($user, User::getWithInfo($hardOnline->user_id));
			}
			return $user;
		}else{
			// No hard online
			return true;
		}	
	}else{
		//User log in
		return $currentUser;
	}
});

Broadcast::channel('privateChat.{roomId}', function ($user, $roomId) {

	//Add Chat history if man
	$user = User::getWithInfo($user->id);
	if($user['man'] === true){
		$history = new ChatHistory();
		$history->room_id = $roomId;
		$history->save();
	}

	return $user;
	// return $user->room->contains($roomId);
});
