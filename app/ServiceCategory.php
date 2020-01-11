<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends _adminPanel
{
  protected $single               = 'serviceCategory';
  protected $multi                = 'serviceCategories';        
  protected $route                = [ 'prefix' => 'admin/' ];
  protected $link                 = "/serviceCategory/";

  protected $columns  = [
    ['name' => 'id'],
    ['name' => 'name'],  
    [
      'name' => 'menu',
      'caption' => 'Show in menu',
      'attributes' => [
        ['id' => -1,'name' => 'no'],
        ['id' => 1,'name' => 'yes']
      ],
    ], 
    [
      'name'         => 'services',
      'relationMany' => 'service', 
      'list'         => [
        ['name' => 'id'],
        ['name' => 'name'],    
        ['name' => 'price'], 
        [
          'name' => 'category',
          'caption' => 'category',
          'relationBelongsToOne' => 'category.name',
        ],                     
      ],
      'settings' => [
        'route'        => [ 'prefix' => 'admin/', 'r' => 'service' ],
        'attach'       => true,
        'detach'       => true,
      ]        
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
      'required'  => false,
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
      'path'            => 'media/services/category',
      'fileName'        => '`id`',
      'maxFileSize'     => '5mb',
      'fileType'        => ['image/*',],  
      'required'        => false,
    ]
  ];

    public function service(){
      //
      return $this->belongsToMany('App\Service');
    } 

  public function __construct(){
      parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }  
}
