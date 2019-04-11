<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatHardOnline extends Model
{

    //
    protected $guarded = ['id','created_at','updated_at'];


    public function user()
    {
        return $this->belongsToOne('App\User');
    } 
}
