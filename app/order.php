<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends _adminPanel
{
  protected $guarded =[];
  protected $single    = 'order';
  protected $multi     = 'orders';        
  protected $edit     = false;        
  protected $add     = false;        
  protected $delete     = false;      
  protected $order       = ['row' => 'id','order' => 'DESC'];  

  protected $columns  = [
    [
      'name' => 'id',
      'caption' => 'Id',
    ],
    [
      'name' => 'product_id',      
      'caption' => 'Product',
      'relation' => false,
    ],
    [
            'name' => 'user_id',
            'caption' => 'email',
            'relation' => 'user.email',
    ],
    [
      'name' => 'name',
    ],
    [
      'name' => 'category',
    ],
    [
      'name' => 'status',
      'relation' => 'status.name',
    ],
    [
      'name' => 'method',
    ],
    [
      'name' => 'transaction',
    ],
    [
      'name' => 'value',
    ],
    [
      'name' => 'created_at',
      'caption'     => 'created at',
      'timeFormat'  => 'j M Y G:i' 
    ],
  ];

  public function __construct(){
      parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }


  public function user(){
    return $this->belongsTo('App\User');
  }

  public function status()
  {
    return $this->belongsTo('App\OrderStatus');
  }  
}
