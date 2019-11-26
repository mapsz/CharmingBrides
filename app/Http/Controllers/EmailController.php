<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Mail;
use App\Man;
use App\Jobs\EmailSendJob;

class EmailController extends Controller
{
  public function index(){
    $data = [
      "user" => Auth::User()->role,
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

    return view('admin.pages.vue')->with('vue','admin-email')->with('data',json_encode($data));
  }


  public function send(Request $Request){

    ini_set('memory_limit', '-1');

    $data = $Request->all();

    //All men
    if($data['all']){
      $men = Man::with('user')->get();      
      $data['list'] = $men->toArray();
    };

    foreach ($data['list'] as $v) {

      $d['email'] = $v;
      $d['subject'] =$data['subject'];   
      $d['email'] = ($data['all']) ? $v['user']['email'] : $v['user_id'];
      $d['name'] =$v['name'];
      $d['content'] = $data['content'];


      $this->dispatch((new EmailSendJob($d)->onQueue('low')));
    }

    return response()->json(['error' => '0','data' => 123]);
  }
}
