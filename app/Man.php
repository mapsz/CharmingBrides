<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Man extends _adminPanel
{
  protected $single               = 'man';
  protected $multi                = 'men';        
  protected $route                = [ 'prefix' => 'admin/' ];
  protected $link                 = "/man/";
  protected $order                = ['row' => 'id','order' => 'DESC'];  
  

  protected $activateSearch = [
    'paramsRoute' => '/parametrs/men',
    'search' =>[   
        [
          'name'=>'search',
          'type'=>'inputText',
        ],
        [
          'name'=>'age',
          'type'=>'fromTo',
          'from'=>18,                          
          'to'=>99,
          'fromDef'=>18,
          'toDef'=>99,
          'fromName'=>'ageFrom',
          'toName'=>'ageTo',
        ], 
        [
          'name'=>'country',
          'type'=>'select',
          'def'=>0,
        ],   
        [
          'name'=>'favorites',
          'type'=>'checkbox',
          'def'=>false,
        ], 
      ]                        
    ];

  protected $columns  = [
    [
      'name' => 'id',
      'relation' => 'user.id'
    ],
    ['name' => 'favorite'],
    ['name' => 'name'],
    [
      'name' => 'surname',
      'caption' => 'surname',
    ], 
    ['name' => 'country'],
    [
      'name' => 'birth',
      'caption' => 'age',
      'timeFormat'  => 'age'
    ],     
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
    ],
    [
      'name' => 'created_at',
      'timeFormat'  => 'j F Y G:i'       
    ]
  ];  

  protected $inputs   = [
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
      [ //favorite
        'name' => 'favorite',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Yes'], 
          ['id' => 0,'name' => 'No'],
        ],
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
        'name' => 'birth',
        'type' => 'date',
        'example' => '05/32/1980',
      ],     
      [ //Photo
        'name'            => 'photo',
        'type'            => 'file',
        'maxFileCount'    => 8,
        'path'            => 'media/gallery',
        'fileName'        => '`parentId`_`rand`',
        'maxFileSize'     => '5mb',
        'fileType'        => ['image/*',],  
        'main'            => '`parentId`_0',
        'default'         => 'no_photo_man.jpg',
        'required' => false,
      ],
      [ //country
        'name' => 'country',
        'type' => 'text',
        'example' => 'Germany',
        'required' => false,
      ],
      [ //city
        'name' => 'city',
        'type' => 'text',
        'example' => 'Frankfurt',
        'required' => false,
      ],
      [ //phoneNumber
        'name' => 'phoneNumber',
        'type' => 'text',
        'example' => '+74 88844772',
        'required' => false,
      ],
      [ //weight
        'name' => 'weight',
        'type' => 'number',
        'example' => '80',
        'required' => false,
      ],
      [ //height
        'name' => 'height',
        'type' => 'number',
        'example' => '185',
        'required' => false,
      ],      
      [ //education
        'name' => 'education',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Associate Degree'], 
          ['id' => 2,'name' => 'College'],
          ['id' => 3,'name' => 'High School'],
          ['id' => 4,'name' => 'Student'],
          ['id' => 5,'name' => 'University'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],
      [ //Profession
        'name' => 'profession',
        'type' => 'text',
        'required' => false,
        'example' => 'Fireman',
      ],
      [ //maritial
        'name' => 'maritial',
        'caption' => 'Maritial status',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Never married'], 
          ['id' => 2,'name' => 'Divorced'],
          ['id' => 3,'name' => 'Widowed'],
          ['id' => 0,'name' => 'Other'],
        ],   
      ],  
      [ //children
        'name' => 'children',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 1], 
          ['id' => 2,'name' => 2],
          ['id' => 3,'name' => 3],
          ['id' => 4,'name' => 4],
          ['id' => 5,'name' => '5+'],
          ['id' => 0,'name' => 'none'],
        ],
      ],
      [ //smoking
        'name' => 'smoking',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Yes'], 
          ['id' => 0,'name' => 'No'],
        ],
      ],
      [ //alcohol
        'name' => 'alcohol',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Socially'], 
          ['id' => 2,'name' => 'Yes'], 
          ['id' => 0,'name' => 'No'],
        ],      
      ],
      [ //info
        'name' => 'info',
        'type' => 'textarea',
        'row' => 5,
        'required' => false,
      ],
      [ //hair
        'name' => 'hair',
        'caption' => 'Hair Color',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Black'], 
          ['id' => 2,'name' => 'Blond'],
          ['id' => 3,'name' => 'Brown'],
          ['id' => 4,'name' => 'Fair'],
          ['id' => 5,'name' => 'Red'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],   
      [ //eyes
        'name' => 'eyes',
        'caption' => 'Eyes Color',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Blue'], 
          ['id' => 2,'name' => 'Green'],
          ['id' => 3,'name' => 'Grey'],
          ['id' => 4,'name' => 'Hazel'],
          ['id' => 0,'name' => 'Other'],
        ],   
      ],
      [ //preffer from
        'name' => 'preffer_from',
        'caption' => 'Preffer Girl Age From',
        'type' => 'number',
        'example' => '25',
        'required' => false,
      ],
      [ //preffer to
        'name' => 'preffer_to',
        'caption' => 'Preffer Girl Age To',
        'type' => 'number',
        'example' => '45',
        'required' => false,
      ],
      [ //girl_weight_from
        'name' => 'girl_weight_from',
        'caption' => 'Preffer Girl Weight From',
        'type' => 'number',
        'example' => '55',
        'required' => false,
      ],
      [ //girl_weight_to
        'name' => 'girl_weight_to',
        'caption' => 'Preffer Girl Weight To',
        'type' => 'number',
        'example' => '70',
        'required' => false,
      ],
      [ //girl_height_from
        'name' => 'girl_height_from',
        'caption' => 'Preffer Girl Height From',
        'type' => 'number',
        'example' => '160',
        'required' => false,
      ],
      [ //girl_height_to
        'name' => 'girl_height_to',
        'caption' => 'Preffer Girl Height To',
        'type' => 'number',
        'example' => '170',
        'required' => false,
      ],
      [ //girl_hair
        'name' => 'girl_hair',
        'caption' => 'Preffer Girl Hair',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Black'], 
          ['id' => 2,'name' => 'Blond'],
          ['id' => 3,'name' => 'Brown'],
          ['id' => 4,'name' => 'Fair'],
          ['id' => 5,'name' => 'Red'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],
      [ //girl_smoking
        'name' => 'girl_smoking',
        'caption' => 'Preffer Girl Smoking',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Yes'], 
          ['id' => 0,'name' => 'No'],
        ],
      ],
      [ //girl_education
        'name' => 'girl_education',
        'caption' => 'Preffer Girl Education',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Associate Degree'], 
          ['id' => 2,'name' => 'College'],
          ['id' => 3,'name' => 'High School'],
          ['id' => 4,'name' => 'Student'],
          ['id' => 5,'name' => 'University'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],
      [ //girl_proffesion
        'name' => 'girl_proffesion',
        'caption' => 'Preffer Girl Proffesion',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Associate Degree'], 
          ['id' => 2,'name' => 'College'],
          ['id' => 3,'name' => 'High School'],
          ['id' => 4,'name' => 'Student'],
          ['id' => 5,'name' => 'University'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],
      [ //girl_maritial
        'name' => 'girl_maritial',
        'caption' => 'Preffer Girl Maritial Status',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Never married'], 
          ['id' => 2,'name' => 'Divorced'],
          ['id' => 3,'name' => 'Widowed'],
          ['id' => 0,'name' => 'Other'],
        ],  
        'required' => false,
      ],
      [ //girl_children
        'name' => 'girl_children',
        'caption' => 'Preffer Girl Children',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 1], 
          ['id' => 2,'name' => 2],
          ['id' => 3,'name' => 3],
          ['id' => 4,'name' => 4],
          ['id' => 5,'name' => '5+'],
          ['id' => 0,'name' => 'none'],
        ],
        'required' => false,
      ],
      [ //girl_info
        'name' => 'girl_info',
        'caption' => 'About Girl',
        'type' => 'textarea',
        'row' => 5,
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

  protected function getMoreInfo($row,$value){

      foreach ($this->inputs as $v) {
        if($v['name'] != $row) continue; 
        if(!isset($v['attributes'])) return $value;
        foreach ($v['attributes'] as $a) {
          if($a['id'] === $value) return $a['name'];
        }
      }

      return $value;
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
