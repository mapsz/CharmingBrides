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