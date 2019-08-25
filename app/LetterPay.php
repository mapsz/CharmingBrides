<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LetterPay extends Model
{
    protected $guarded =[];


    //Relations
    public function Letter()
    {
        return $this->belongsTo('App\Letter');
    }   
}
