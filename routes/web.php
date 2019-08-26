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

//Membership
Route::get('/memberships/current', 'MembershipController@getCurrent');
Route::get('/memberships/history', 'MembershipController@getHistory');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/registration', 'Auth\RegisterController@index')->name('registration');
Route::get('/profile', 'ManController@edit')->name('profile')->middleware('auth');


//Man
Route::get('/man/{id}','ManController@index')->name('showMan');
Route::get('/registration/man', 'ManController@create')->name('manCreate');
Route::put('/man', 'ManController@put')->name('manStore');

//chat
Route::get('/chat', 'ChatController@index')->name('chat');
Route::get('/room', 'ChatController@getRoom')->name('getRoom');
// Route::post('/chat/invite', 'ChatController@intviteCompanion')->name('chatInvite');

Route::post('/chat/messages', 'ChatController@storeMessage')->name('storeMessage');
Route::get('/chat/messages', 'ChatController@getMessages')->name('getMessages');
Route::get('/chat/recentRooms', 'ChatController@getRecentRooms')->name('getRecentRooms');
Route::get('/chat/getHardOnline', 'ChatController@getHardOnline')->name('getHardOnline')->middleware(['auth','admin']);


//Girls
//show
Route::get('/girl/{id}','GirlController@index')->name('showGirl');
//get special Ladies
Route::get('/girls/special/ladies','GirlController@getSpecialLadies')->name('getSpecialLadies');


//Admin Chat
Route::post('/chat/admin/setOnline', 'ChatController@setHardOnline')->name('setHardOnline')->middleware(['auth','admin']);
Route::delete('/chat/admin/deleteOnline', 'ChatController@deleteHardOnline')->name('deleteHardOnline')->middleware(['auth','admin']);

// Letter
Route::group(['middleware' => ['auth']],function(){
  
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
  Route::put('/'.$model, ucfirst($model).'Controller@_put')->name($namePrefix.'put'.ucfirst($model));                  //Put
  Route::post('/'.$model, ucfirst($model).'Controller@_post')->name($namePrefix.'post'.ucfirst($model));               //Post
  Route::delete('/'.$model, ucfirst($model).'Controller@_destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
  Route::delete('/'.$model.'/detach', ucfirst($model).'Controller@_detach')->name($namePrefix.'detach'.ucfirst($model));    //Detach
  Route::put('/'.$model.'/attach', ucfirst($model).'Controller@_attach')->name($namePrefix.'attach'.ucfirst($model));        //Attach  
  Route::get('/'.$model.'/search', ucfirst($model).'Controller@_search')->name($namePrefix.'search'.ucfirst($model));  //Search
  Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@_getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
  Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@_fileUpload')
        ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload    

  // Men
  $model = 'man';
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
