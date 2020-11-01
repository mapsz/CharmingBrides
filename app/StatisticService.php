<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatisticService extends Model
{



  
  public function service(){
    return $this->belongsTo('App\Service');
  } 
  public function agent(){
    return $this->belongsTo('App\User','agent_id');
  } 
  public function man(){
    return $this->belongsTo('App\User','man_id');
  } 
  public function girl(){
    return $this->belongsTo('App\User','girl_id');
  } 
}
