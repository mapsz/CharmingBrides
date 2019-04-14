<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Man extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    } 
}
