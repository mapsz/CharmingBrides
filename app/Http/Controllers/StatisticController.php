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
    return view('admin.pages.vue')->with('vue','statistic-agents');
  }  

  public function getAgents(){

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


    return response()->json(['error' => '0', 'data' => $agentsDb]);

  }
}
