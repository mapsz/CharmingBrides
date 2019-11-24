<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sign extends _adminPanel
{

  protected $single         = 'sign';
  protected $multi          = 'signs';        
  protected $route          = [ 'prefix' => 'admin/' ];

  protected $edit      = false; 
  protected $delete    = false; 
  protected $add       = false; 

  protected $columns  = [
    [
      'name' => 'id',
    ],
    [
      'name' => 'from_id',
      'relation' => 'from.email'
    ],
    [
      'name' => 'to_id',
      'relation' => 'to.email'
    ],
    [
      'name' => 'from_confirmed',
    ],
    [
      'name' => 'to_confirmed',
    ],
    [
      'name' => 'created_at',
    ],
  ];  


  public static function sendSign($userId,$toId,$like = 1){

      //Get existing
      $sign = self
          ::where(function ($query)use($userId,$toId) {
            $query->where  ('from_id', '=', $userId)
                  ->where('to_id',   '=', $toId);
          })            
          ->orWhere(function ($query)use($userId,$toId) {
            $query->where  ('from_id', '=', $toId)
                  ->where('to_id',   '=', $userId);
          })  
          ->orderBy('id', 'desc')
          ->first();

      
      if(!$sign){
        //Make new
        $sign = new self;
        $sign->from_id        = $userId;
        $sign->to_id          = $toId;
        $sign->from_confirmed = $like;
      }else{
        //Edit existing
        if     ($sign->from_id == $userId){
          $sign->from_confirmed = $like;
        }
        elseif ($sign->to_id == $userId){
          $sign->to_confirmed   = $like;
        }else{
          return false;
        }
      }

      if(!$sign->save()) return false;

      return true;

  }

  public function __construct(){
    //
    parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }

  protected $guarded = [];

  //Relations
  public function to()
  {
      return $this->belongsTo('App\User');
  }   

  //Relations
  public function from()
  {
      return $this->belongsTo('App\User');
  }         
}