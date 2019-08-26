<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Girl extends _adminPanel
{

  protected $single         = 'girl';
  protected $multi          = 'girls';        
  protected $route          = [ 'prefix' => 'admin/' ];
  protected $page           = "";
  protected $edit           = false;
  protected $link           = "/girl/";

  protected $columns  = [
    [
      'name' => 'id',
      'relation' => 'user.id'
    ],
    ['name' => 'name'],
    [
      'name'        => 'birth',
      'timeFormat'  => 'Y M j'
    ],
    ['name' => 'location'],
    [
      'name' => 'user_id',
      'caption' => 'email',
      'relation' => 'user.email',
    ],
    [
      'name'        => 'confirm',
      'caption'     => 'confirm',
      'relation'    => 'user.role',
      'component'   => 'admin-girl-confirm-component',          
    ],
    [          
      'name' => 'agent',
      'caption' => 'agent',
      'relationBelongsToOne' => 'agent.name',
    ],          
  ];  

  protected $inputs    = [
      [ //Email
        'name'      => 'email', 
        'parent'    => 'User',
        'type'      => 'email',
        'example'   => 'anna.pavlova@gmail.com'
      ],    
      [ //Name
        'name' => 'name',
        'type' => 'text',
        'example' => 'Anastasiya'
      ],
      [ //Birth
          'name' => 'Birth',
          'type' => 'date',
          'example' => '05/32/1980',
          'required' => false,
      ],
      [ //location
        'name' => 'location',
        'type' => 'text',
        'example' => 'Kiev',
        'required' => false,
      ],
      [ //weight
        'name' => 'weight',
        'type' => 'number',
        'example' => '65',
        'required' => false,        
      ],
      [ //height
        'name' => 'height',
        'type' => 'number',
        'example' => '170',
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
      [ //religion 
        'name' => 'religion',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Christianity'], 
          ['id' => 2,'name' => 'Islam'],
          ['id' => 3,'name' => 'Hinduism'],
          ['id' => 4,'name' => 'Buddhism'],
          ['id' => 5,'name' => 'Judaism'],
          ['id' => 6,'name' => 'Nonreligious'],
          ['id' => 0,'name' => 'Other'],
        ],   
      ],
      [ //education
        'name' => 'education',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Associate Degree'], 
          ['id' => 2,'name' => 'College'],
          ['id' => 3,'name' => 'High Schoo'],
          ['id' => 4,'name' => 'Student'],
          ['id' => 5,'name' => 'University'],
          ['id' => 0,'name' => 'Other'],
        ],
      ],
      [ //Profession
        'name' => 'profession',
        'type' => 'text',
        'required' => false,
        'example' => 'Hairdresser',
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
      [ //english
        'name' => 'english',
        'type' => 'radio',
        'required' => false,
        'attributes' => [
          ['id' => 1,'name' => 'Fluent'], 
          ['id' => 2,'name' => 'Good'], 
          ['id' => 3,'name' => 'Medium'], 
          ['id' => 4,'name' => 'Poor'], 
          ['id' => 5,'name' => 'Some'],
        ],      
      ],
      [ //Languages
        'name' => 'languages',
        'caption' => 'Other Languages',
        'type' => 'text',
        'required' => false,        
        'example' => "Spanish(native), Chinese(fluent)",
      ],
      [ //preffer from
        'name' => 'prefferFrom',
        'caption' => 'Preffered men age',
        'type' => 'text',
        'required' => false,
        'example' => 'From',
      ],
      [ //preffer to
        'name' => 'prefferTo',
        'caption' => 'Preffered men age',
        'type' => 'text',
        'required' => false,
        'example' => 'To',
      ],
      [ //info
        'name' => 'info',
        'type' => 'textarea',
        'row' => 5,
        'required' => false,
        'example' => "I like travelling, it is the most exciting thing in the world! It`s so wonderful and interesting to learn new places, to meet new people! I dream to see the whole world! I am calm, intelligent and well bred lady. I am here because I know that foreign men are more clever, more intellignet and more family oriented than our Ukrainian men. My character is not difficult, I can find compromises, I am tender in love, that`s why i am sure my man will be happy with me. And of course I dream he will make me happy also. \n" . "I am not looking for ideal man, but I would like he would have kind heart, open character, of course he should be attentive to me, because I want to feel his attention, understanding and support. I promise to give all myself to such man!"
      ],
      [ //first letter subject
        'name' => 'firstLetterSubject',
        'type' => 'text',
        'required' => false,
        'example' => 'My dream is to love',
      ],
      [ //first letter
        'name' => 'firstLetter',
        'caption' => 'First Letter',
        'type' => 'textarea',
        'row' => 5,
        'required' => false,
        'example' => "My name is Alena) I am a student of Melitopol State University of Municipal Management; I entered the department of hotel business because I like to manage people, to develop different ideas and turn them into reality. My plan is to start a network of hotels one day! :)\n" ."I am living in the city of Melitopol, I live with my family in a private house. My family is not very big: my mother Lyudmila, father Yuriy and my little sister Kate. I love to spend time with my family, my sister and I love to draw together) \n" . "When I have free time I read. I enjoy reading fantasy books, among my favorite authors are Joan Rowling and Ray Bradbury. I also love watching films, my favorite genre is melodrama. Sometimes I watch a film to relax, but I try to find the films that provoke deep thinking, that have an intricate plot and make you feel what the characters feel. Sometimes books and films teach us a lot. Do you agree?\n" .  "My main hobby is dancing. I dance in different styles, but my favorite one is jazz-funk! When I perform on the stage I get so many emotions, this is a real charge of positive! I take part in different competitions and fashion shows, too! From time to time I take part in photo sessions and I truly like this! Do you like to take pictures? Or you prefer to be taken pictures of?\n" .  "I love animals and I have 2 pets) They are a cat and a dog! What a funny combination! Surprisingly, but they are good friends! \n" .  "I love travelling, but at the moment I have travelled only around my country. I have visited different cities among them are Kiev, Lviv, Zaporozhye. I enjoyed my stay in Kiev, it is a very beautiful city with fascinating architecture and places to see. It has also a great choice of shopping malls! You can find an activity to your liking in Kiev! It has a lot to offer! \n" .  "I can call myself a kind and brave, communicative and cheerful lady! I am always glad to help my close ones and friends. I decided to post my profile on the site because I believe that my chosen one is not necessarily in Ukraine, he can be anywhere! So I widen the horizons of my search and hope to find you, my soul mate, very soon!\n" . "I have lots of dreams, but my biggest one is to create a happy family with a strong-willed, caring, kind, generous and loving man! A man, who can support me in any situation and be my second half. \n" .  "I am looking forward to your letter!\n" .  "Warmly,\n" .  "Alena\n",
      ],
      [ //for admin name
        'name' => 'forAdminName',
        'caption' => 'Name (For admin)',
        'type' => 'text',
        'required' => false,
      ],
      [ //for admin surname
        'name' => 'forAdminSurname',
        'caption' => 'Suename (For admin)',
        'type' => 'text',
        'required' => false,
      ],
      [ //for admin fathers name
        'name' => 'forAdminFathersName',
        'caption' => 'Fathersname (For admin)',
        'type' => 'text',
        'required' => false,
      ],
      [ //for admin number
        'name' => 'forAdminPhoneNumber',
        'caption' => 'Phone Number (For admin)',
        'type' => 'text',
        'required' => false,
      ],
      [ //Photo
          'name'            => 'photo',
          'type'            => 'file',
          'maxFileCount'    => 8,
          'path'            => 'media/gallery',
          'fileName'        => '`parentId`_`i`',
          'maxFileSize'     => '5mb',
          'fileType'        => ['image/*',],   
      ],
      [ //Passport
          'name'            => 'passport',
          'type'            => 'file',
          'maxFileCount'    => 1,
          'path'            => 'media\passport',
          'fileName'        => '`parentId`_`i`',
          'maxFileSize'     => '10mb',
          'fileType'        => ['image/*',],   
      ],
      [ //Video
          'name'            => 'video',
          'type'            => 'file',
          'maxFileCount'    => 1,
          'path'            => 'media\video',
          'fileName'        => '`parentId`_`i`',
          'maxFileSize'     => '20mb',
          'fileType'        => ['video/*',], 
          'required'        => false,  
      ],
  ];

  public function __construct(){
    //
    parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
  }

  public function validate($request){
    $val = $request->validate([
        'email'          => 'required|unique:users',
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
      if($c['name'] == 'confirm'){
        $this->columns[$k] = [
          'name'        => 'confirm',
          'caption'     => 'confirm',
          'relation'    => 'user.role',
          'attributes'  => [
              ['id' => 0,'name' => 'uncofirm'],
              ['id' => 1,'name' => 'confirm'],
          ]
        ];
      }
      if($c['name'] == 'agent'){
        unset($this->columns[$k]);
      }
    }

  }

  public function getPage(){

    //Admin
    if (Auth::user() &&  Auth::user()->role == 4) {
       $this->page = 'admin.pages.girls';
    }

    //Agent
    if($this->page == "") $this->page = '_adminPanel.list';
    //Return page
    return $this->page;             
  }

  protected function getDbData(){
    
    //Admin
    if (Auth::user() &&  Auth::user()->role == 4) {
      parent::getDbData();
      return;
    }

    //Agent
    $this->dbData = Girl::whereHas('agent', function($q){
        $q->where('id', '=', '1');
    })->get();

  }

  protected function getMoreInfo($row,$value){

      foreach ($this->inputs as $v) {
        if($v['name'] != $row) continue; 
        if(!isset($v['attributes'])) return $value;
        foreach ($v['attributes'] as $a) {
          if($a['id'] == $value) return $a['name'];
        }
      }

      return $value;
  }

	protected $guarded = [];

	public function user()
  {
      return $this->belongsTo('App\User');
  }
  
  public function agent()
  {
      return $this->belongsToMany('App\Agent');
  } 
       
}