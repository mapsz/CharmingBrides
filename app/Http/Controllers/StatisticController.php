<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;
use App\Agent;
use App\Girl;
use App\LetterPay;
use App\Letter;
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
    $query = $query->with('user.statisticService.agent');
    $query = $query->with('user.statisticService.agent.agent');
    $query = $query->with('user.statisticService.man');
    $query = $query->with('user.statisticService.man.man');
    $query = $query->with('user.statisticService.girl');
    $query = $query->with('user.statisticService.girl.girl');
    $query = $query->with('user.statisticService.service');



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

  public function getGirls(Request $request){

    //Dates
    $dates = [];
    $dates['from'] = Carbon::createFromTimestamp($request->from);
    $dates['to'] = Carbon::createFromTimestamp($request->to);
    
    //Letters
    if('letters'){
      //Short letter
      $query = new Letter;
      
      //With
      $query = $query->with('LetterPay');
      $query = $query->with('user.man');
      $query = $query->with('user.girl');

      //Where
      $query = $query->whereHas('LetterPay');
      $query = $query->where('created_at', '>', $dates['from']);
      $query = $query->where('created_at', '<', $dates['to']);
      $query = $query->whereRaw('Length(body) < 200');
      if($request->id > 0){
        $query = $query->where('user_id',$request->id);
      }

      //Get
      $shortLetters = $query->get();

      //Long letter    
      $query = new Letter;
      
      //With
      $query = $query->with('LetterPay');
      $query = $query->with('user.man');
      $query = $query->with('user.girl');


      //Where
      $query = $query->whereHas('LetterPay');
      $query = $query->where('created_at', '>', $dates['from']);
      $query = $query->where('created_at', '<', $dates['to']);
      $query = $query->whereRaw('Length(body) > 199');
      if($request->id > 0){
        $query = $query->where('user_id',$request->id);
      }

      //Get
      $longLetters = $query->get();
    }

    //All
    if('all'){
      $all = [];

      //Long letters
      foreach ($longLetters as $key => $value) {
        $v['letter'] = $value;
        $v['girl'] = $value->user_id . '_' . $value->user->girl->name;
        $v['client'] = $value->to_user_id . '_' . $value->toUser->man->name;
        $v['type'] = 'LongLetter';
        $v['item_timestamp'] = $value->created_at->timestamp;
        $v['item_date'] = Carbon::parse($value->created_at)->format('Y-m-d');
        array_push($all,$v);
      }      

      //Short letter
      foreach ($shortLetters as $key => $value) {
        $v['letter'] = $value;
        $v['girl'] = $value->user_id . '_' . $value->user->girl->name;
        $v['client'] = $value->to_user_id . '_' . $value->toUser->man->name;
        $v['type'] = 'ShortLetter';
        $v['item_timestamp'] = $value->created_at->timestamp;
        $v['item_date'] = Carbon::parse($value->created_at)->format('Y-m-d');
        array_push($all,$v);
      }

      //Sort by date
      usort($all, function ($a, $b) { return ($a['item_timestamp'] <=> $b['item_timestamp']); });

      //By date
      $byDate = [];
      foreach ($all as $key => $row){
        //Date
        if(!isset($byDate[$row['item_date']])){
          $byDate[$row['item_date']] = [];
        }
        //Type
        if(!isset( $byDate [$row['item_date']] [$row['type']])){
          $byDate[$row['item_date']][$row['type']] = [];
        }
        //Girl
        if(!isset( $byDate [$row['item_date']] [$row['type']] [$row['girl']])){
          $byDate [$row['item_date']] [$row['type']] [$row['girl']] = [];
        }

        //Client
        if(!isset( $byDate [$row['item_date']] [$row['type']] [$row['girl']] [$row['client']])){
          $byDate [$row['item_date']] [$row['type']] [$row['girl']] [$row['client']] = [];
        }
        array_push($byDate[$row['item_date']][$row['type']][$row['girl']][$row['client']], $row);
      }
    }

    return response()->json(['error' => '0', 'data' => $byDate]);
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
