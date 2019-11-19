<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ManFavorite extends Model
{
  public static function put($girl_user_id){

    $man_user_id = Auth::User()->id;

    $mf = new ManFavorite();

    $mf->girl_user_id = $girl_user_id;
    $mf->man_user_id = $man_user_id;
    $mf->timestamps = false;

    return $mf->save();
  }


  protected $guarded = [];
  public $timestamps = false;
}
