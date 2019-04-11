<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Man extends Model
{
    //
    protected $fillable = ['user', 'name','surname','birth'];

    public function user()
    {
        return $this->belongsTo('App\User');
    } 
}
