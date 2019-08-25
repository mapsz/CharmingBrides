<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    //
    protected $guarded = ['id'];

    public static function g($name){
      return Self::where('name', '=', $name)->first()->value;
    }

    public static function s($name,$value){
      return Self::where('name',$name)->update(['value' => $value]);
    }
}
