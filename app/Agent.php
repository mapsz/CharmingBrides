<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends _adminPanel
{

    protected $single    = 'agent';
    protected $multi     = 'agents';        
    // protected $route     = 'admin/agent';
    protected $route     = [ 'prefix' => 'admin/' ];

    protected $edit      = true;
    // protected $delete    = false;
    // protected $add       = false;
    protected $parentId = true;

    protected $columns  = [
      [
        'name' => 'id',
        'caption' => 'user id',
        'relation' => 'user.id'
      ],
      ['name' => 'name'],
      ['name' => 'email','caption' => 'email', 'relation' => 'user.email'],
      ['name' => 'location'],
      ['name' => 'created_at','timeFormat' => 'j M y G:i'],
      [
        'name'         => 'girls',
        'relationMany' => 'girl', 
        'list'         => [
          ['name' => 'id'],
          ['name' => 'name'],
          [
            'name' => 'user_id',
            'caption' => 'email',
            'relation' => 'user.email',
          ],       
          ['name' => 'birth'],
          [
            'name' => 'location',
            'caption' => 'city',
          ],
          [
            'name' => 'agent',
            'caption' => 'agent',
            'relationBelongsToOne' => 'agent.name',
          ],            
        ],
        'settings' => [
          'route'        => [ 'prefix' => 'admin/', 'r' => 'girl' ],
          'attach'       => true,
          'detach'       => true,
          'attachSearch' =>[
            'paramsRoute' => '/parametrs/girl',
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
                  'name'=>'location',
                  'type'=>'select',
                  'caption'=>'location',
                  'def'=>0,
                ],
              ]                        
            ],
        ]        
      ],
    ];
    protected $inputs    = [
              [ //Email
                'name'      => 'email', 
                'parent'    => 'User',
                'type'      => 'email',
                'example'   => 'anna.pavlova@gmail.com'
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
              ],                
              [ //Location
                'name' => 'location',
                'type' => 'text',
              ],       
    ];

    public function __construct(){
        parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
    }

    public function attachValidate($request){

        $val = $request->validate([
            'modelId'         => 'required',
            'targetId'        => 'required',
            'targetName'      => 'required',
        ]);

        //Check already attached
        $girl = new Girl();
        $girl = $girl->with('agent')->find($request->targetId);

        if(count($girl->agent) > 0 ){
          return ['error' => 1, 'text' => $girl->name.' already attached to agent'];
        }

        return ['error' => 0];
    }
  

    protected $guarded = [];
    public function user(){
      //
      return $this->belongsTo('App\User');
    }

    public function girl(){
      //
      return $this->belongsToMany('App\Girl');
    } 
}
