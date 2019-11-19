<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Letter;
use Illuminate\Support\Facades\Auth;

class ManNotification extends Model
{
  public static function get(){

    if(!Auth::check()) return;

    if(Auth::User()->role > 2) return;

    //Letters
    $l = Letter::where('to_user_id',Auth::User()->id)->whereNull('read')->count();
    $s = Sign::where('to_id',Auth::User()->id)->whereNull('read')->count();


    return ['l' => $l,'s' => $s];
  }
}
