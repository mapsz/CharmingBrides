<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $guarded = ['id','created_at','updated_at'];

    public function user()
    {
        return $this->belongsToOne('App\User');
    }   

    public function room()
    {
        return $this->belongsTo('App\Room');
    }   
}
