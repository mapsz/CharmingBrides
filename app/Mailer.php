<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailer extends Model
{
  
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
