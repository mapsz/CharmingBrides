<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Carbon;

class SignController extends _adminPanelController
{

    protected $model     = 'App\Sign';

    public function __construct(){
        parent::__construct($this->model);
    }

    public function _index(){
      $data['search'] = [
        ['name'=>'period',
        'type'=>'fromToDate',
        'fromDef'=>Carbon::now()->subMonth(2)->format('Y-m-d'),
        'toDef'=>Carbon::now()->format('Y-m-d'),
        'fromName'=>'periodFrom',
        'toName'=>'periodTo'],      
      ];

      return view('admin.pages.vue')->with('vue','admin-signs')->with('data', json_encode($data));
    }

    public function getSigns (Request $request){
      $data = $this->model::whereHas('from',function($q){
                              $q->whereHas('man');
                            })
                            ->whereHas('to',function($q){
                              $q->whereHas('girl');
                            })
                            ->with('from.man')
                            ->with('to.girl')
                            ->with('to.girl.agent')
                            ->orderBy('created_at', 'desc');

      if(Auth::User()->role == 3){
        $id = Auth::User()->id;
        $data = $data->whereHas('to.girl.agent',function($q)use($id){
          $q->where('user_id',$id);
        });
      }

      //Period
      if(isset($request->search)){
        $search = json_decode($request->search);
        $dates = [
          'from' => $search->periodFrom,
          'to'   => $search->periodTo
        ];
        $data = $data->where(function($q)use($dates) {
                                    $q->where('created_at', '>=', $dates['from'])
                                    ->where('created_at', '<=', $dates['to']);
                                  });
      }      

      $data = $data->paginate(20);

      foreach ($data as $k => $v) {
        if($v->created_at == NULL){
          $data[$k]->created_at = '2019-10-25 00:00:00';
        }
      }

      return response()->json(['error' => '0', 'data' => ['signs' =>$data->toArray()['data'], 'pages' => $data->lastPage()]]);

    }

    public function matched(){

      return view('pages.matched');
    }

    public function matches(Request $request){

      // $id = 58441;
      $id = Auth::user()->id;

      //Get matches
      $matches = $this->model
                ::where(function ($query)use($id) {
                  $query->where  ('from_id', '=', $id)
                        ->orWhere('to_id',   '=', $id);
                })            
                ->where('from_confirmed','=',1)
                ->where('to_confirmed','=',1)
                ->with('to.girl','to.man')
                ->with('from.girl','from.man')
                ->get();

      //Clear same genre matches
      foreach ($matches as $key => $v) {
        if(
          ($v->to->girl == NULL && $v->from->girl == NULL) ||
          ($v->to->girl != NULL && $v->from->girl != NULL)
        ){
          unset($matches[$key]);
        }
      }

      //Formate data
      $fMatches = [];
      foreach ($matches as $key => $v) {
        //Get companion
        if($v->to->id == $id){
          $companion = $v->from;
        }else{
          $companion = $v->to;
        }
        $match['companion'] = User::getWithInfo($companion->id,$companion);
        $match['id'] = $v->id;
        $match['date'] = $v->updated_at;
        array_push($fMatches,  $match);

      }

      $matches = $fMatches;

      if(is_array($matches)){
        return response()->json(['error' => '0','data' => $matches]);
      }else{
        return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      }   
    }

    public function likedyou(Request $request){

      return view('pages.likedyou');    
    }

    public function getLikedyou(Request $request){
      $id = Auth::user()->id;
      //Get matches
      $likes = $this->model                
                ::where('to_id',   '=', $id)          
                ->where('from_confirmed','=',1)
                ->where('to_confirmed','=',0)
                ->with('to.girl','to.man')
                ->with('from.girl','from.man')
                ->paginate(50);

      //Clear same genre matches
      foreach ($likes as $key => $v) {
        if(
          ($v->to->girl == NULL && $v->from->girl == NULL) ||
          ($v->to->girl != NULL && $v->from->girl != NULL)
        ){
          unset($likes[$key]);
        }
      }

      //Formate data
      $fMatches = [];
      foreach ($likes as $key => $v) {
        //Get companion
        if($v->to->id == $id){
          $companion = $v->from;
        }else{
          $companion = $v->to;
        }
        $match['companion'] = User::getWithInfo($companion->id,$companion);
        $match['id'] = $v->id;
        $match['date'] = $v->updated_at;
        array_push($fMatches,  $match);

      }

      $likes = $fMatches;   

      if(is_array($likes)){
        return response()->json(['error' => '0','data' => $likes]);
      }else{
        return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      }    
    }

    public function like(Request $request){

      // //Validation @@@
      // $request->validate([
      //     'user_id'=> new CurrentUserOrAdmin
      // ]);


      if(isset($request->fromId)){
        $userId = $request->fromId;
      }else{
        $userId  = Auth::user()->id;
      }

      //Get data      
      $toId    = $request->toId;
      $like    = $request->like;
      //Check data
      if(
        !$userId ||
        !$toId  ||
        ($like != 1 && $like != -1)
      ){
        return response()->json(['error' => '2', 'text' => 'something gone wrong']);
      }

      if(isset($request->a) && $request->a > 0){
        $a = new $this->model;

        $a = $a::find($request->a);
        $a->to_confirmed = $like;

        // $a->update();

        $a->save();

        return response()->json(['error' => '0']);
      }


      if(!$this->model::sendSign($userId,$toId,$like))
        return response()->json(['error' => '4', 'text' => 'something gone wrong']);

      return response()->json(['error' => '0']);
    }

}
