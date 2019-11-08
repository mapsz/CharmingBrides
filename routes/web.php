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

//    Info pages
//  About
//about us
Route::get('/about', function () {return view('pages.home');});

//  Help
//faq
Route::get('/faq', function () {return view('pages.home');});
//how to start
Route::get('/howtostart', function () {return view('pages.home');});
//  Contacts
Route::get('/contacts', function () {return view('pages.home');});

//Main
Route::get('/', function () {
    return view('pages.home');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return view('pages.home');
})->name('home');


//Pay
Route::get('/order',         'PayController@order');

//Membership
Route::get('/memberships',         'MembershipController@index');
Route::get('/memberships/get/all', 'MembershipController@getMemberships');
Route::get('/memberships/current', 'MembershipController@getCurrent');
Route::get('/memberships/history', 'MembershipController@getHistory');


//Profile
Route::get('/registration', 'Auth\RegisterController@index')->name('registration');
Route::get('/register', 'Auth\RegisterController@index');
Route::get('/profile', 'ManController@profile')->name('profile')->middleware('auth');
Route::get('/profile/membership', 'ManController@profileMembership')->name('profileMembership')->middleware('auth');
Route::get('/profile/edit', 'ManController@edit')->middleware('auth');


//Signs
Route::get('/matched', 'SignController@matched')->middleware('auth')->name('matched');
Route::get('/matches', 'SignController@matches')->middleware('auth');
Route::get('/likedyou', 'SignController@likedyou')->middleware('auth');
Route::get('/get/likedyou', 'SignController@getLikedyou')->middleware('auth');
Route::post('/like', 'SignController@like')->middleware('auth');


//Man
Route::get('/man/{id}','ManController@index')->name('showMan');
Route::get('/registration/man', 'ManController@create')->name('manCreate');
Route::put('/man', 'ManController@put');
Route::post('/profile', 'ManController@post');

//chat
Route::get('/chat', 'ChatController@index')->name('chat');
Route::get('/room', 'ChatController@getRoom')->name('getRoom');
Route::post('/chat/stop', 'ChatController@stopChat');
Route::post('/chat/pay', 'ChatController@payChat');
Route::post('/chat/read', 'ChatController@roomRead');
Route::post('/chat/invite', 'ChatController@chatInvite');

Route::put('/chat/messages', 'ChatController@storeMessage')->name('storeMessage');
Route::get('/chat/messages', 'ChatController@getMessages')->name('getMessages');
Route::get('/chat/recentRooms', 'ChatController@getRecentRooms')->name('getRecentRooms');
Route::put('/chat/history', 'ChatController@startHistory');
Route::get('/chat/getHardOnline', 'ChatController@getHardOnline')->name('getHardOnline')->middleware(['auth','agent']);
Route::get('/chat/search/girl', 'ChatController@searchGirl')->middleware(['auth','agent']);

// WebSocketsRouter::webSocket('/my-websocket', \App\WebSocketHandlers\MyCustomWebSocketHandler::class);


//Girls
//show
Route::get('/girl/{id}','GirlController@index')->name('showGirl');
Route::get('/all/girls','GirlController@allGirls')->name('allGirls');
Route::get('/locations/girl','GirlController@locations');
Route::get('/registration/girl', 'GirlController@create')->name('girlCreate');
Route::get('/all/girl/search', 'GirlController@search');
//get special Ladies
Route::get('/girls/special/ladies','GirlController@getSpecialLadies')->name('getSpecialLadies');
Route::post('/girl/file/upload', 'GirlController@_fileUpload');
Route::put('/girl', 'GirlController@_put');



//Admin Chat
Route::post('/chat/admin/setOnline', 'ChatController@setHardOnline')->name('setHardOnline')->middleware(['auth','agent']);
Route::delete('/chat/admin/deleteOnline', 'ChatController@deleteHardOnline')->name('deleteHardOnline')->middleware(['auth','agent']);


//Auth
Route::group(['middleware' => ['auth']],function(){

  // Letter
  $model = 'letter';
  $namePrefix = "";                
  Route::get('/letters', ucfirst($model).'Controller@index')->name($namePrefix.$model);                      
  Route::put('/'.$model, ucfirst($model).'Controller@put')->name($namePrefix.'put'.ucfirst($model));           
  Route::get('/'.$model.'/get/companions', ucfirst($model).'Controller@getCompanions')->name('getLetterCompanions');  
  Route::get('/'.$model.'/get', ucfirst($model).'Controller@getLetters')->name('getLetters');  
  Route::get('/'.$model.'/user/{user?}', ucfirst($model).'Controller@getUserLetters')->name('getUserLetters'); 
  Route::put('/'.$model.'/pay', ucfirst($model).'Controller@payLetter')->name('payLetter');                  
  Route::get('/'.$model.'/long/length', ucfirst($model).'Controller@getLongLetterLength')
    ->name('getLongLetterLength');                                                                         
  Route::post('/'.$model.'/long/length', ucfirst($model).'Controller@postLongLetterLength')
    ->name('postLongLetterLength');

  Route::get('/letter/get/girls', ucfirst($model).'Controller@getGirls')->name('getGirls');  

  Route::get('/letter/get/companion', ucfirst($model).'Controller@getSingleCompanion')->name('getSingleCompanion');    
  Route::post('/letter/read', ucfirst($model).'Controller@read');

  //Girl 
  $model = 'girl';
  $namePrefix = "";
  Route::get('/search/'.$model, ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search        
  Route::delete('/'.$model.'/file/delete', ucfirst($model).'Controller@_fileDelete');  //Delete file   
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get');

});


//Agent
Route::group(['name' => 'admin', 'prefix' => 'admin', 'middleware' => ['auth','agent']],function(){

  //Home
  Route::get('/',         'GirlController@_index')->name('admin');

  // Girls
  $model = 'girl';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::get('/'.$model.'/edit/{id}', ucfirst($model).'Controller@_edit')->name($namePrefix.'edit'.ucfirst($model));     //edit
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload  
  Route::delete('/'.$model.'/file/delete', ucfirst($model).'Controller@_fileDelete');  //Delete file          
  Route::post('/'.$model.'/file/main', ucfirst($model).'Controller@_fileMain');  //Delete file          
 
  // Men
  $model = 'man';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::get('/'.$model.'/edit/{id}', ucfirst($model).'Controller@_edit')->name($namePrefix.'edit'.ucfirst($model));     //edit  
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload   

  // Letter
  $model = 'letter';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload   


  //Letter
  Route::get('/letter/girls', 'letterController@getAdminGirls');

});

//Admin
Route::group(['name' => 'admin', 'prefix' => 'admin', 'middleware' => ['auth','admin']],function(){

  //Special Ladies
  Route::delete('/girls/special/ladies','admin\GirlController@deleteSpecialLadies')->name('deleteSpecialLadies');
  Route::put('/girls/special/ladies','admin\GirlController@putSpecialLadies')->name('putSpecialLadies');
  Route::post('/girls/confirm','GirlController@confirm')->name('girlConfirm');

  // Membership
  $model = 'membership';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::get('/'.$model.'/edit/{id}', ucfirst($model).'Controller@_edit')->name($namePrefix.'edit'.ucfirst($model));     //edit  
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload   
  //Membership
  Route::get('/memberships/current', 'MembershipsController@getCurrentMembership');
  //H tory
  Route::get('/memberships/history', 'admin\MembershipsController@getHistoryMembership');
  //attach membership
  Route::put('/memberships/attach', 'MembershipController@attachMembership');

  // Agents
  $model = 'agent';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::get('/'.$model.'/edit/{id}', ucfirst($model).'Controller@_edit')->name($namePrefix.'edit'.ucfirst($model));     //edit
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload   

  // Signs
  $model = 'sign';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
  Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@_get')->name($namePrefix.'get'.ucfirst($model));           //Get
  Route::get('/'.$model.'/create', ucfirst($model).'Controller@_create')->name($namePrefix.'create'.ucfirst($model));  //Create
  Route::get('/'.$model.'/edit/{id}', ucfirst($model).'Controller@_edit')->name($namePrefix.'edit'.ucfirst($model));     //edit
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload   


	//Chat histories
	//index
	Route::get('/chat/history', 'admin\ChatHistoryController@index')->name('adminChatHistoryIndex');	



});
