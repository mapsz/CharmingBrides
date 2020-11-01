<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;
use App\Agent;
use App\Girl;
use App\LetterPay;
use App\Membership;
use App\StatisticService;
use Illuminate\Support\Facades\Validator;

class StatisticController extends Controller
{

  public function getAgents(Request $request){
    $dates = [];
    $dates['from'] = Carbon::createFromTimestamp($request->from);
    $dates['to'] = Carbon::createFromTimestamp($request->to);
    
    $query =  new Agent;
      

    //With    
    $query = $query->with('girl.user.room.chats');
    $query = $query->with(['girl.user.room.user' => function($q){
      $q->whereHas('man');
    }]);

    $query = $query->with(['girl' => function($q)use($dates){      
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
    }]);

    $query = $query->with('user.statisticService');



    //Get
    $agentsDb = $query->get();

    $agentsFormated = [];
    $out = [];
    foreach ($agentsDb as $k => $agent) {
      if(count($agent->girl) == 0) continue;
 
      $agentsFormated[$agent->id] = [];

      foreach ($agent->girl as $girl) {        
        array_push($agentsFormated[$agent->id],$girl->id);
      }
    }


    foreach ($agentsDb as $k => $agent) {
      $agentsDb[$k]['statistic_services'] = $agent->user->statisticService;

      $agentsDb[$k]['statistic_services_total'] = 0;

      foreach ($agentsDb[$k]['statistic_services'] as $statistic_services) {
        $agentsDb[$k]['statistic_services_total'] += $statistic_services->paid_to_agent;
      }
    }


    return response()->json(['error' => '0', 'data' => $agentsDb]);

  }

  public function getMemberships(Request $request){
    $dates = [];
    $dates['from'] = Carbon::createFromTimestamp($request->from);
    $dates['to'] = Carbon::createFromTimestamp($request->to);    
    $memberships = Membership::with(['user' => function($q)use($dates){
        $q
          ->where('membership_user.created_at', '>', $dates['from'])
          ->where('membership_user.created_at', '<', $dates['to'])
          ->with('man');
      }])
      ->get();

    return response()->json(['error' => '0', 'data' => $memberships]);
  }

  public function getStatisticService(){

    $ss = new StatisticService;

    $ss = $ss->with('service');
    $ss = $ss->with('agent');
    $ss = $ss->with('agent.agent');
    $ss = $ss->with('man');
    $ss = $ss->with('man.man');
    $ss = $ss->with('girl');
    $ss = $ss->with('girl.girl');

    $ss = $ss->get();

    return response()->json(['error' => '0', 'data' => $ss]);
  }

  //Statistic
  public function putService(Request $request){

    $validate = [
      'date'            => ['required','date'],
      'agentId'         => ['required','exists:users,id'],
      'girlId'          => ['required','exists:users,id'],
      'manId'           => ['required','exists:users,id'],
      'paidtoAgent'     => ['required','numeric'],
      'serviceId'       => ['required','exists:services,id'],
    ];

    // dd($request->all());

    Validator::make($request->all(), $validate)->validate();


    $ser = new StatisticService;
    $ser->date            = $request->date;
    $ser->service_id      = $request->serviceId;
    $ser->agent_id        = $request->agentId;
    $ser->girl_id         = $request->girlId;
    $ser->man_id          = $request->manId;
    $ser->paid_to_agent   = $request->paidtoAgent;

    if(!$ser->save()){
      return response()->json(['error' => '1']);
    }
      
    return response()->json(['error' => '0', 'data' => $ser]);

  }




  //Indexses  
  public function index(){
    return view('admin.pages.vue')->with('vue','statistic');
  }  
  public function agents(){
    return view('admin.pages.vue')->with('vue','statistic-agents');
  }  
  public function memberships(){
    return view('admin.pages.vue')->with('vue','statistic-memberships');
  }  
}
