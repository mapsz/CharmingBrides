<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Man extends _adminPanel
{
  protected $single    = 'man';
  protected $multi     = 'men';        
  protected $route     = [ 'prefix' => 'admin/' ];
  protected $link      = "/man/";

  protected $columns  = [
    [
      'name' => 'id',
      'relation' => 'user.id'
    ],
    ['name' => 'name'],
    [
      'name' => 'surname',
      'caption' => 'surname',
    ], 
    // [
    //   'name' => 'age',
    //   'caption' => 'age',
    // ],     
    [
      'name' => 'user_id',
      'caption' => 'email',
      'relation' => 'user.email',
    ], 
    [
      'name' => 'balance',
    ],
    [
      'name' => 'membership',
      'component' => 'men-membership',
    ]
  ];  
//age, location, created_at, last online, favorites,
//send admin email

  // Извините, David занят в данный момент, пожалуйста, попробуйте начать чат с David позже, вы также можете начать новый чат с любым из мужчин, которые сейчас на сайте.


  protected $inputs    = [
      [ //Email
        'name'      => 'email', 
        'parent'    => 'User',
        'type'      => 'email',
        'example'   => 'michael.smith@gmail.com'
      ],
      [ //Password
        'name'      => 'password',
        'type'      => 'password',
        'parent'    => 'User',
      ],    
      [ //Password confirm
        'name'      => 'confirm_password',
        'caption'   => 'Confirm Password',
        'type'      => 'password',
        'parent'    => 'User',
        'confirm'   => true,
        'hash'      => false,
      ],  
      [ //Name
        'name' => 'name',
        'type' => 'text',
        'example' => 'Michael'
      ],
      [ //Surname
        'name' => 'surname',
        'type' => 'text',
        'example' => 'Smith',
        'required' => false,
      ],
      [ //Birth
        'name' => 'Birth',
        'type' => 'date',
        'example' => '05/32/1980',
        'required' => false,
      ],        
  ];

  public function __construct(){
      parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }

  public function validate($request){
    $val = $request->validate([
        'email'          => 'required|unique:users',
        'password'       => 'required',
        'name'       => 'required',
    ]);

    return $val;
  }



  public function setColumns($columns){

    $this->columns = $columns;

    //Admin
    if (Auth::user() &&  Auth::user()->role == 4) {
      return;
    } 

    foreach ($this->columns as $k => $c) {
      if($c['name'] == 'membership'){
        unset($this->columns[$k]);
      }
    }

  }



  protected $guarded = [];

  public function user()
  {
      return $this->belongsTo('App\User');
  } 
}
