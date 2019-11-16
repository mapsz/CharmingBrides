<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialLady;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GirlController extends _adminPanelController
{
    protected $model     = 'App\Girl';

    public function __construct(){
        parent::__construct($this->model);
    }


    public function create(){
        //Get Model
        $model = new $this->model();
        // $model->setInputs($inputs);  //Inputs

        //Get Data
        $inputs = $model->getInputs();  //Inputs
        $route = $model->getRoute();        
        $route['prefix'] = "";
        //Encode
        $inputs     = json_encode($inputs);
        $route      = json_encode($route);

        //View
        return view('pages.registrationGirl')
                    ->with('inputs', $inputs)
                    ->with('route',$route);    
    }


    public function _index(){
      if(Auth::User()->role == 3){

        //Get Model
        $model = new $this->model();

        $model->setColumns([
              [
                'name'        => 'confirm',
                'caption'     => 'confirm',
                'relation'    => 'user.role',       
              ], 
              ['name' => 'id'],
              ['name' => 'name'],
              [
                'name' => 'user_id',
                'caption' => 'email',
                'relation' => 'user.email',
              ],       
              [
                'name'        => 'birth',
                'caption'     => 'age',
                'timeFormat'  => 'age'
              ],
              [
                'name' => 'location',
                'caption' => 'city',
              ]
            ]);  

        //Only agent girls
        $custom = $model;        
        $id = Auth::User()->agent->id;
        $callback = function($q)use($id) {
          $q->where('id','=',$id);
        };              
        $custom = $custom->whereHas('agent' , $callback)->with(['agent' => $callback]);
        $model->setCustomQueries($custom);

        //Get Data
        $data   = $model->getData(); // Data
        $inputs = $model->getInputs();  //Inputs
        $names   = $model->getNames();  //Names
        $page   = $model->getPage();    //Page
        $route = $model->getRoute(); 
        $settings = $model->getSettings(); 
       
        //Encode
        $data   = json_encode($data);
        $inputs = json_encode($inputs);
        $names   = json_encode($names);
        $route   = json_encode($route);
        $settings   = json_encode($settings);

        return view($page)
          ->with('data', $data)
          ->with('inputs', $inputs)
          ->with('name', $names)
          ->with('route',$route)
          ->with('settings',$settings);

      }else{
        return parent::_index();
      }
    }


    public function index($id) {
      //Auth
      $auth = Auth::user();

      //User man
      if($auth != null){
        $userMan = User::getWithInfo($auth->id)['man'];   //@@@
      }else{
        $userMan = 0;
      }

      //Get Girl
      $model = new $this->model();
      $model->setWhere([['column' => 'user_id','condition' => '=','value' => $id]]);
      //Set columns
      $columns = [
        [
          'name'    => 'photo',
          'file'    => 'image',
        ],
        [
          'name' => 'id',
          'relation' => 'user.id'
        ],
        ['name' => 'name'],
        ['name' => 'birth'],
      ];

      if($auth != null){
        array_push( $columns,['name' => 'height']);
        array_push( $columns,['name' => 'weight']);
        array_push( $columns,['name' => 'hair']);
        array_push( $columns,['name' => 'eyes']);
        array_push( $columns,['name' => 'religion']);
        array_push( $columns,['name' => 'education']);
        array_push( $columns,['name' => 'profession']);
        array_push( $columns,['name' => 'maritial']);
        array_push( $columns,['name' => 'children']);
        array_push( $columns,['name' => 'smoking']);
        array_push( $columns,['name' => 'alcohol']);
        array_push( $columns,['name' => 'english']);
        array_push( $columns,['name' => 'languages']);
        array_push( $columns,['name' => 'id']);
        array_push( $columns,['name' => 'location']);
        array_push( $columns,['name' => 'info']);
        if($userMan > 2){
          array_push($columns,  [
                                  'name' => 'user_id',
                                  'caption' => 'email',
                                  'relation' => 'user.email',
                                ]);
          array_push($columns,['name' => 'forAdminName']);
          array_push($columns,['name' => 'forAdminSurname']);
          array_push($columns,['name' => 'forAdminFathersName']);
          array_push($columns,['name' => 'forAdminPhoneNumber']);
          array_push($columns,['name' => 'firstLetter']);
          array_push($columns,['name' => 'firstLetterSubject']); 
        }
      }
      $model->setColumns($columns);
      $data = $model->getData();

      //Girl not found
      if(count($data['data']) == 0){
        return redirect('/');
      }

      //Edit data
      $girl = $data['data'][0];
      if(isset($girl['birth']))$girl['age'] = Carbon::parse($girl['birth'])->age;
      if(isset($girl['email']))$girl['email'] = $girl['user_id'];unset($girl['user_id']);
      if(isset($girl['info']))$girl['info'] = strip_tags($girl['info']);
      if(isset($girl['firstLetter']))$girl['firstLetter'] = strip_tags($girl['firstLetter']);
      if(isset($girl['firstLetterSubject'])){$girl['firstLetterSubject'] = strip_tags($girl['firstLetterSubject']);}


      foreach ($girl as $k => $v) {
        $girl[$k] = $this->model::getMoreInfo($k,$v);
      }         

      return view('pages.girl')
                ->with('girl',json_encode($girl))
                ->with('auth',$auth)
                ->with('userIsMan',$userMan);
    }    

    public function allGirls(){
      return view('pages.vue')->with('vue','girls');
    }

    public function newGirls(){

      $model = new $this->model();

      $model->setPerPage(20);
      $model->setInfoColumns();
      $model->setOrder(['row'=>'id','order'=>'DESC']);
      $where = [['column' => 'created_at','condition' => '>=','value' => Carbon::now()->subMonth()->toDateTimeString()]];
      $model->setWhere($where);

      $data = $model->getData();
      $data = $data['data'];
      $settings = $model->getSettings();

      foreach ($data as $k => $v) {
        $data[$k]['age']     = Carbon::parse($v['birth'])->age;
      }

      $girls = json_encode($data);
      $settings = json_encode($settings);

      return view('pages.girls')->with('girls',$girls)->with('settings',$settings);

    }

    public function userSearch(request $request){

      $model = new $this->model();

      //Set up
      $model->setPerPage(20);
      $model->setInfoColumns();
      $model->setOrder(['row'=>'id','order'=>'DESC']);

      if(!isset($request->search)) return response()->json(['error' => '1']);

      // decode
      $search = json_decode($request->search);

      //where
      $where = [];

      //location
      if(isset($search->location) && $search->location != '0'){
        array_push($where, ['column' => 'location','condition' => '=','value' => $search->location]);
      }

      //maritial
      if(isset($search->maritial) && $search->maritial != '0'){
        array_push($where, ['column' => 'maritial','condition' => '=','value' => $search->maritial]);
      }

      //location
      if(isset($search->children)){
        array_push($where, ['column' => 'children','condition' => '<=','value' => $search->children]);
      }

      //age from
      if(isset($search->ageFrom)){
        $from = Carbon::now()->subYear($search->ageFrom)->format('Y-m-d');
        array_push($where, ['column' => 'birth','condition' => '<','value' => $from]);
      }
      //age to
      if(isset($search->ageTo)){
        $to = Carbon::now()->subYear($search->ageTo)->format('Y-m-d');
        array_push($where, ['column' => 'birth','condition' => '>','value' => $to]);
      }

      //custom
      $custom = $model;
      if(isset($search->search)){
        $val = $search->search;      
        $custom = $custom->where(function($q)use($val) {
          $q->where('name','LIKE','%'.$val.'%')
            ->orWhere('user_id','LIKE','%'.$val.'%');
        });
      }
      //Remove unconfirm
      $custom = $custom->whereHas('user',function($q){
        $q->where('role',1);
      });

      $model->setCustomQueries($custom);

      $model->setWhere($where);

      //Get data
      $data = $model->getData();   
      if(!is_array($data)) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        

      $data = $data['data'];
      $settings = $model->getSettings();   

      foreach ($data as $k => $v) {
        $data[$k]['age']     = Carbon::parse($v['birth'])->age;
      }      

      $girls = json_encode($data);
      $settings = json_encode($settings);
      $data = ['data' => $girls, 'settings' => $settings];
      return response()->json(['error' => '0','data' => $data]);
    }


    public function search(request $request){

      $model = new $this->model();

      //Set up
      $model->setPerPage(50);

      //Order
      $model->setOrder(['row'=>'id','order'=>'DESC']);
      if(isset($request->order)){
        $order = json_decode($request->order);
        if($order->name != ""){
          $model->setOrder(['row'=>$order->name,'order'=>$order->type]);
        }
      }

      if(!isset($request->search)) return response()->json(['error' => '1']);

      // decode
      $search = json_decode($request->search);

      //where
      $where = [];

      //location
      if(isset($search->location) && $search->location != '0'){
        array_push($where, ['column' => 'location','condition' => '=','value' => $search->location]);
      }

      if(isset($search->children)){
        array_push($where, ['column' => 'children','condition' => '<=','value' => $search->children]);
      }

      //age from
      if(isset($search->ageFrom)){
        $from = Carbon::now()->subYear($search->ageFrom)->format('Y-m-d');
        array_push($where, ['column' => 'birth','condition' => '<','value' => $from]);
      }
      //age to
      if(isset($search->ageTo)){
        $to = Carbon::now()->subYear($search->ageTo)->format('Y-m-d');
        array_push($where, ['column' => 'birth','condition' => '>','value' => $to]);
      }


      $custom = $model;    
      if(isset($search->search)){
        $val = $search->search;      
        $custom = $custom->where(function($q)use($val) {
          $q->where('name','LIKE','%'.$val.'%')
            ->orWhere('user_id','LIKE','%'.$val.'%');
        });
      }
       //Only agent girls
      
      if(Auth::User()->role == 3){       
        $id = Auth::User()->agent->id;
        $callback = function($q)use($id) {
          $q->where('id','=',$id);
        };              
        $custom = $custom->whereHas('agent' , $callback)->with(['agent' => $callback]);
        $model->setCustomQueries($custom);
      }
      
      //No agent
      if(Auth::User()->role == 4){   
        if(isset($search->agents) && !$search->agents){
          $custom = $custom->whereDoesntHave('agent');
        }
      }

      $model->setCustomQueries($custom);

      $model->setWhere($where);

      //Get data
      $data = $model->getData();   
      if(!is_array($data)) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        
      $data = $data['data'];
      $settings = $model->getSettings();   

      $girls = json_encode($data);
      $settings = json_encode($settings);
      $data = ['data' => $girls, 'settings' => $settings];
      return response()->json(['error' => '0','data' => $data]);
    }

    public function _search(request $request){

      return self::search($request);
    }

    public function getGirlsParametr(){

      $params = [];
      //Locations
      $q = DB::select( DB::raw(
                   "SELECT location, COUNT(*)
                    FROM girls
                    GROUP BY location
                    HAVING COUNT(*) > 1"
                  ));

      $param = [];
      foreach ($q as $key => $v) {
        array_push($param, $v->location);
      }
      $params['location'] = $param;

      //Professions
      $q = DB::select( DB::raw(
                   "SELECT profession, COUNT(*)
                    FROM girls
                    GROUP BY profession
                    HAVING COUNT(*) > 1"
                  ));
      $param = [];
      foreach ($q as $key => $v) {
        array_push($param, $v->profession);
      }
      $params['profession'] = $param;

      //Maritial
      $q = DB::select( DB::raw(
                   "SELECT name, COUNT(*)
                    FROM maritials
                    GROUP BY name"
                  ));
      $param = [];
      foreach ($q as $key => $v) {
        array_push($param, $v->name);
      }
      $params['maritial'] = $param;

      return response()->json(['error' => '0','data' => $params]);
    }

    public function getSpecialLadies(){

      $speacialLadies = SpecialLady::get();

      $model = new $this->model();

      $where = [];
      foreach ($speacialLadies as $lady) {
        array_push($where, ['column' => 'user_id','condition' => '=','value' => $lady->user_id,'or' => true]);
      }

      $model->setInfoColumns();

      $model->setWhere($where);
      $data = $model->getData();
      $data = $data['data'];

      foreach ($data as $k => $v) {
        $data[$k]['age']     = Carbon::parse($v['birth'])->age;
      }

      return response()->json(['error' => '0', 'data' => $data]);
    }

    public function deleteSpecialLadies(request $request){
        $delete = SpecialLady::where('user_id','=',$request->id);
        // dd($delete);
        $id = $delete->delete();
        return response()->json(['error' => '0', 'id' => $id]);  
    }

    public function putSpecialLadies(request $request){
        //Check max specials
        $maxSpecials = 8;
        $currentSpecials = SpecialLady::count();
        if(($maxSpecials - $currentSpecials) <= 0){
            return response()->json(['error' => '1', 'text' => 'Max speacial ladies - '.$maxSpecials]);
        }

        $put = new SpecialLady;

        $put->timestamps = false;
        $put->user_id = $request->id;       

        //Save
        if($put->save()){
            return response()->json(['error' => '0']);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function confirm(request $request){

      $user = User::find($request->id);

      if(!$user) return response()->json(['error' => '1', 'text' => 'something gone wrong']);

      if($request->confirm)
        $user->role = 0;
      else
        $user->role = 1;

      if(!$user->save()) return response()->json(['error' => '1', 'text' => 'something gone wrong']);

      return response()->json(['error' => '0']);
    }
    
}
