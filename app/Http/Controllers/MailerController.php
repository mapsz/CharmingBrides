<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Girl;
use App\Man;
use App\Mailer;
use App\User;
use Auth;

class MailerController extends Controller
{
  public function index(){

    $data = [
      "user" => Auth::User()->role,
      'girlSearch' =>[
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
                          'name'=>'locations',
                          'type'=>'select',
                          'def'=>0,
                        ]
                    ],
      'manSearch' =>[   
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

    if(Auth::User()->role == 4){
     array_push($data['girlSearch'],  
                        [
                          'name'=>'agents',
                          'type'=>'checkbox',
                          'def'=>false,
                        ]);
    }



    return view('admin.pages.vue')->with('vue','mailer')->with('data',json_encode($data));
  }

  public function getRecentGirls(){
      //Settings
      $columns = [
        [
          'name' => 'id',
          'relation' => 'user.id'
        ],
        ['name' => 'name'],
        ['name' => 'birth','timeFormat'  => 'age' ],
        ['name' => 'location'],
        [          
          'name' => 'agent',
          'caption' => 'agent',
          'relationBelongsToOne' => 'agent.name',
        ],  
        [
          'name'        => 'created_at',
          'caption'     => 'created at',
          'timeFormat'  => 'j M Y G:i' 
        ], 
      ];
      $order = ['row' => 'id', 'order' => 'desc'];
      $count = 10;

      //Convert to array
      foreach ($columns as $k => $v) {
        $columns[$k] = (array)$v;
      }

      //Get Model
      $model = new Girl;

      //No agent
      $custom = $model;
      $custom = $custom->whereDoesntHave('agent');
      $model->setCustomQueries($custom);

      //Settings
      $model->setColumns($columns);
      $model->setOrder($order);
      $model->setPerPage($count);

      //Get Data
      $data   = $model->getData(); 

      return response()->json(['error' => '0', 'data' => $data]);
  }

  public function getRecentMen(){
      //Settings
      $columns = [
        [
          'name' => 'id',
          'relation' => 'user.id'
        ],
        ['name' => 'name'],
        ['name' => 'surname'],
        ['name' => 'birth','timeFormat'  => 'age' ],
        [
          'name'        => 'created_at',
          'caption'     => 'created at',
          'timeFormat'  => 'j M Y G:i' 
        ], 
      ];
      $order = ['row' => 'id', 'order' => 'desc'];
      $count = 10;

      //Convert to array
      foreach ($columns as $k => $v) {
        $columns[$k] = (array)$v;
      }

      //Get Model
      $model = new Man;

      //Settings
      $model->setColumns($columns);
      $model->setOrder($order);
      $model->setPerPage($count);

      //Get Data
      $data   = $model->getData(); 

      return response()->json(['error' => '0', 'data' => $data]);
  }

  public function getMenCount(){

    $count = Man::count();

    if(!$count) return response()->json(['error' => '1']);

    return response()->json(['error' => '0', 'data' => $count]);
  }

  public function putMailer(request $request){

    //Validate
    if(!isset($request->from) || count($request->from) < 1){
      return response()->json(['error' => '1', 'text' => 'Girls not set']);
    }
    //is to all
    if(isset($request->toall) && $request->toall){
      $to = Man::pluck('user_id')->toArray();
    }else{
      if(!isset($request->to) || count($request->to) < 1){
        return response()->json(['error' => '1', 'text' => 'Men not set']);
      }else{
        $to = $request->to;
      }      
    }

    $m = new Mailer();
    $m->user_id         = Auth::User()->id;
    $m->from_user_ids   = json_encode($request->from);
    $m->to_user_ids     = json_encode($to);
    $m->progress        = count($request->from)*count($to);
    $m->type            = $request->type;

    if(!$m->save()) return response()->json(['error' => '1']);

    return response()->json(['error' => '0', 'data' => $m->id]);
  }

  public function getMailers(){

    $m = Mailer::with('user')->orderBy('id','DESC')->get();

    $data = [];
    foreach ($m as $k => $v) {
      $d['id']          = $v->id;
      $d['user']        = $v->user->email;
      $d['from']        = count(json_decode($v->from_user_ids));
      $d['to']          = count(json_decode($v->to_user_ids));
      $d['type']        = $v->type;
      $d['progress']    = $v->progress;
      $d['created_at']  = $v->created_at->format('j F Y G:i');
      array_push($data, $d);
    }

    return response()->json(['error' => '0', 'data' => $data]);
  }

  public function searchGirls(request $request){
  }

}
