<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Agent;
use App\Girl;
use App\LetterPay;

class StatisticController extends Controller
{
  public function index(){
    return view('admin.pages.vue')->with('vue','statistic');
  }  
  public function agents(){

    // ->whereHas('agent', function($q)use($id){
    //   $q->where('id', '=', $id);
    // })
    // ->where(function($q) {
    //   $q->where('Cab', 2)
    //   ->orWhere('Cab', 4);
    // })


    // $a = 
    // Agent::whereHas('girl', function($q){
    //   $q->whereHas('user', function($q2){
    //     $q2->whereHas('letter', function($q3){
    //       $q3->whereHas('LetterPay');
    //     });
    //   });
    // })
    // ->with('girl.user.letter.LetterPay')    
    // ->get()->toArray();



    $agentsDb = Agent::
      whereHas('girl.user.letter.LetterPay' , function($q){
        $q->where('created_at', '>', '2019-11-19 15:25:30');
      })
      ->with(['girl' => function($q){
        $q->whereHas('user.letter.LetterPay' , function($q){
          $q->where('created_at', '>', '2019-11-19 15:25:30');
        })
        ->with(['user.letter' => function($q){
          $q->whereHas('LetterPay' , function($q){
            $q->where('created_at', '>', '2019-11-19 15:25:30');
          })
          ->with('LetterPay');
        }]);
      }])
      ->get();

    $agentsFormated = [];
    foreach ($agentsDb as $agent) {      
      if(count($agent->girl) == 0) continue;
 
      $agentsFormated[$agent->id] = [];

      // dd($agent);
      foreach ($agent->girl as $girl) {        
        array_push($agentsFormated[$agent->id],$girl->id);
      }
    }

    // dd($agentsFormated);


    return view('admin.pages.vue')->with('vue','statistic-agents')->with('data', json_encode($agentsDb));
  }  
}
