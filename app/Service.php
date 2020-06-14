<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends _adminPanel
{
  protected $single               = 'service';
  protected $multi                = 'services';        
  protected $route                = [ 'prefix' => 'admin/' ];
  protected $link                 = "/service/";

  protected $columns  = [
    ['name' => 'id'],
    ['name' => 'name'],    
    ['name' => 'price'],   
    [
      'name' => 'menu',
      'caption' => 'Show in menu',
      'attributes' => [
        ['id' => -1,'name' => 'no'],
        ['id' => 1,'name' => 'yes']
      ],
    ],       
  ];
  
  protected $inputs   = [
    [ 
      'name' => 'name',
      'type' => 'text',
    ],    
    [
      'name'      => 'description', 
      'type'      => 'textarea',
    ],  
    [
      'name' => 'price',
      'type' => 'text',
    ],              
    [
      'name' => 'menu',
      'caption' => 'Show in menu',
      'type' => 'radio',
      'attributes' => [
        ['id' => -1,'name' => 'no'],
        ['id' => 1,'name' => 'yes']
      ],  
    ],  
    [
      'name'            => 'image',
      'type'            => 'file',
      'maxFileCount'    => 1,
      'path'            => 'media/services',
      'fileName'        => '`id`',
      'maxFileSize'     => '5mb',
      'fileType'        => ['image/*',],  
      'required'        => false,
    ]
  ];

  public function __construct(){
      parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }  
 
  public function category(){
    return $this->belongsToMany('App\ServiceCategory');
  }  

}
