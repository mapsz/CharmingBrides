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

Route::group(['middleware' => 'under-construction'], function () {


//    Info pages
//news
Route::get('/news', function () {return view('pages.page')->with('page','news');});
Route::get('/about', function () {return view('pages.page')->with('page','about');});
Route::get('/anti/scam', function () {return view('pages.page')->with('page','antiscam');});
Route::get('/branches', function () {return view('pages.page')->with('page','branches');});
Route::get('/our/couples', function () {return view('pages.page')->with('page','our-couples');});
Route::get('/romantic/tour', function () {return view('pages.page')->with('page','romantic-tour');});
Route::get('/how/to/start', function () {return view('pages.page')->with('page','how-to-start');});
Route::get('/holidays', function () {return view('pages.page')->with('page','holidays');});
Route::get('/faq', function () {return view('pages.page')->with('page','faq');});
Route::get('/contacts', function () {return view('pages.page')->with('page','contacts');});
Route::get('/ukraine', function () {return view('pages.page')->with('page','ukraine');});

//Main
Route::get('/', function () {
    return view('pages.home');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function () {
    return view('pages.home');
})->name('home');


//Notifications
Route::get('/notifications',         'ManNotificationController@get');

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
Route::post('/profile/file/upload', 'ManController@_fileUpload');
Route::delete('/profile/file/delete', 'ManController@_fileDelete');  //Delete file          
Route::post('/profile/file/main', 'ManController@_fileMain');  //Delete file            

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
Route::get('/parametrs/men','ManController@getMenParametr');
Route::get('/all/men/search','ManController@search');
Route::put('/add/girl/favorites','ManFavoriteController@put');
Route::get('/favorite/girls','ManFavoriteController@index');


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
Route::get('/new/girls','GirlController@newGirls')->name('newGirls');
Route::get('/parametrs/girl','GirlController@getGirlsParametr');
Route::get('/registration/girl', 'GirlController@create')->name('girlCreate');
Route::get('/all/girl/search', 'GirlController@userSearch');
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

  //Mailer
  Route::get('/mailer', 'MailerController@index')->name('admin_mailer');
  Route::get('/mailer/recent/girls', 'MailerController@getRecentGirls');
  Route::get('/mailer/recent/men', 'MailerController@getRecentMen');
  Route::get('/mailer/men/count', 'MailerController@getMenCount');
  Route::put('/mailer/send/letters', 'MailerController@putMailer');
  Route::get('/get/mailers', 'MailerController@getMailers');

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
  // Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);                               //Index
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


  Route::get('/letter', 'LetterController@_index')->name($namePrefix.$model);    
  Route::get('/letter/history', ucfirst($model).'Controller@adminLetterHistory');

  //Letter
  Route::get('/letter/girls', 'letterController@getAdminGirls');

  //signs
  Route::get('/signs/', 'SignController@getSigns');  
  Route::get('/sign', 'SignController@_index')->name('admin_sign'); 

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
                                //Index
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


  //Men  
  Route::post('/man/balance/edit', 'ManController@editBalance');
  Route::post('/man/login', 'ManController@loginAdminMan');

  //Orders

    // Signs
  $model = 'order';
  $namePrefix = "admin_";
  Route::get('/'.$model, ucfirst($model).'Controller@_index')->name($namePrefix.$model);


});


});