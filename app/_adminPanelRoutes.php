<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _adminPanelRoutes extends Model
{


  public static function add($model, $namePrefix){
    Route::get('/'.$model, ucfirst($model).'Controller@index')->name($namePrefix.$model);                               //Index
    Route::get('/'.$model.'/get/{id?}', ucfirst($model).'Controller@get')->name($namePrefix.'get'.ucfirst($model));           //Get
    Route::get('/'.$model.'/create', ucfirst($model).'Controller@create')->name($namePrefix.'create'.ucfirst($model));  //Create
    Route::put('/'.$model, ucfirst($model).'Controller@put')->name($namePrefix.'put'.ucfirst($model));                  //Put
    Route::post('/'.$model, ucfirst($model).'Controller@post')->name($namePrefix.'post'.ucfirst($model));               //Post
    Route::delete('/'.$model, ucfirst($model).'Controller@destroy')->name($namePrefix.'delete'.ucfirst($model));        //Delete
    Route::get('/'.$model.'/search', ucfirst($model).'Controller@search')->name($namePrefix.'search'.ucfirst($model));  //Search
    Route::get('/'.$model.'/recent/get', ucfirst($model).'Controller@getRecent')->name($namePrefix.'recent'.ucfirst($model));  //Get Recent 
    Route::post('/'.$model.'/file/upload', ucfirst($model).'Controller@fileUpload')
          ->name($namePrefix.'fileUpload'.ucfirst($model));  //File upload
    Route::get('/'.$model.'/test/{test}', ucfirst($model).'Controller@test')->name($namePrefix.'test'.ucfirst($model));  //Get Recent     
  }



}
