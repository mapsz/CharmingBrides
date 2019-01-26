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
	Route::get('/mans', function () {
	    return view('admin.pages.mans');
	})->name('adminManIndex');

});
