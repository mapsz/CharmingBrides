<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;
use App\Agent;
use App\Girl;
use App\LetterPay;
use App\Membership;

class StatisticController extends Controller
{
  public function index(){
    return view('admin.pages.vue')->with('vue','statistic');
  }  
  public function agents(){
    return view('admin.pages.vue')->with('vue','statistic-agents');
  }  
  public function memberships(){
    return view('admin.pages.vue')->with('vue','statistic-memberships');
  }  

  public function getAgents(Request $request){
    $dates = [];
    $dates['from'] = Carbon::createFromTimestamp($request->from);
    $dates['to'] = Carbon::createFromTimestamp($request->to);
    $agentsDb = Agent::
      whereHas('girl.user.letter.LetterPay' , function($q)use($dates){
        $q->where('created_at', '>', $dates['from'])
          ->where('created_at', '<', $dates['to']);
      })
      ->with(['girl' => function($q)use($dates){
        $q->whereHas('user.letter.LetterPay' , function($q)use($dates){
          $q->where('created_at', '>', $dates['from'])
            ->where('created_at', '<', $dates['to']);
        })
        ->with(['user.letter' => function($q)use($dates){
          $q->whereHas('LetterPay' , function($q)use($dates){
            $q->where('created_at', '>', $dates['from'])
              ->where('created_at', '<', $dates['to']);
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

  public function getMemberships(Request $request){
    $memberships = Membership::with('user')->get();

    return response()->json(['error' => '0', 'data' => $memberships]);
  }
}
