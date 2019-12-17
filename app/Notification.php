<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

  private $noties = ['letters', 'signs', 'news'];
  private $types = ['email', 'pushup'];

  public function get($id,$type = false){

    //Get noties
    $noties = self::where('user_id', '=', $id)->get();
    if(!$noties) return false;

    //Empty
    if(count($noties) == 0){
      //Create first
      if(!self::createFirst($id)) return false;
      //Get noties
      $noties = self::where('user_id', '=', $id)->get()->toArray();
      if(!$noties) return false;
      if(count($noties) == 0) return false;
    }

    $rNoties = [];
    foreach ($this->noties as $n) {
      $rNoties[$n] = [];
      foreach ($this->types as $t) {
        $rNoties[$n][$t] = 0;        
        foreach ($noties as $noty) {
          if($n == $noty['noty'] && $t == $noty['type']){
            $rNoties[$n][$t] = 1;
          }
        }
      }
    }

    if($type){
      return $rNoties[$type];
    }

    return $rNoties;
  }

  public function post($id, $noties){

    foreach ($noties as $n => $notys) {
      foreach ($notys as $t => $v) {
        //Get db
        $db = (new $this)->where('user_id',$id)->where('noty',$n)->where('type',$t);
        //Delete
        if($v == 0 || $v == false){
          if(count($db->get()) > 0){
            if(!$db->delete()){
              return false;
            }
          }
        }
        //Add
        elseif($v == 1 || $v == true){
          if(count($db->get()) == 0){
            $an = new $this;
            $an->user_id = $id;
            $an->noty = $n;
            $an->type = $t;
            if(!$an->save()){
              return false;
            }
          }
        }
      }
    }

    return true;
  }


  public static function createFirst($id){

    //Email
    $n = new self;
    $n->user_id = $id;
    $n->noty = 'sets';
    if(!$n->save()) return false;
    
    return true;   
  }

  public $timestamps = false;
  protected $guarded =[];
  public function user(){
    return $this->belongsTo('App\User');
  }
}
