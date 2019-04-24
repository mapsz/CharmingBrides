<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

Route::get('/', function () {
    return view('pages.home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/registration', 'Auth\RegisterController@index')->name('registration');
Route::get('/profile', 'ManController@edit')->name('profile')->middleware('auth');

Route::get('/registration/man', 'ManController@create')->name('manCreate');
Route::post('/registration/man', 'ManController@store')->name('manStore');

//chat
Route::get('/chat', 'ChatController@index')->name('chat');
Route::get('/room', 'ChatController@getRoom')->name('getRoom');
// Route::post('/chat/invite', 'ChatController@intviteCompanion')->name('chatInvite');

Route::post('/chat/messages', 'ChatController@storeMessage')->name('storeMessage');
Route::get('/chat/messages', 'ChatController@getMessages')->name('getMessages');
Route::get('/chat/recentRooms', 'ChatController@getRecentRooms')->name('getRecentRooms');
Route::get('/chat/getHardOnline', 'ChatController@getHardOnline')->name('getHardOnline')->middleware(['auth','admin']);


//Admin Chat
Route::post('/chat/admin/setOnline', 'ChatController@setHardOnline')->name('setHardOnline')->middleware(['auth','admin']);
Route::delete('/chat/admin/deleteOnline', 'ChatController@deleteHardOnline')->name('deleteHardOnline')->middleware(['auth','admin']);

//Membership
Route::get('/memberships/current', 'admin\MembershipsController@getCurrentMembership');
//History
Route::get('/memberships/history', 'admin\MembershipsController@getHistoryMembership');

//Admin
Route::group(['name' => 'admin', 'prefix' => 'admin', 'middleware' => ['auth','admin']],function(){

	//Home
	Route::get('/', 				'admin\GirlController@index')->name('admin');

	// Girls
	//index
	Route::get('/girls', 			'admin\GirlController@index')->name('adminGirlIndex');
	//Create
	Route::get('/girls/create',		'admin\GirlController@create')->name('adminGirlCreate');	
	//Store
	Route::post('/girls/store', 	'admin\GirlController@store')->name('adminGirlStore');	
	//Show

	//Edit

	//Uspdate

	//Destroy


	// Men
	//index
	Route::get('/men', 					'admin\ManController@index')->name('adminManIndex');
	//Membership
	Route::post('/user/membership', 		'admin\ManController@attachMembership')->name('adminManAttachMembership');

	// Membership
	//index
	Route::get('/memberships', 'admin\MembershipsController@index')->name('adminMembershipIndex');
	//Get
	Route::get('/memberships/get', 'admin\MembershipsController@get')->name('adminMembershipGet');
	//Create
	Route::get('/memberships/create', 'admin\MembershipsController@create')->name('adminMembershipCreate');
	//Store
	Route::post('/memberships', 'admin\MembershipsController@store')->name('adminMembershipStore');

	//Chat histories
	//index
	Route::get('/chat/history', 'admin\ChatHistoryController@index')->name('adminChatHistoryIndex');	
});
