<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterPay;
use App\User;
use App\Membership;
use App\Agent;
use App\Girl;
use App\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Illuminate\Support\Facades\Config;
use App\Rules\CurrentUserOrAdmin;
use Illuminate\Support\Carbon;

class LetterController extends _adminPanelController
{

  protected $model     = 'App\Letter';

  public function __construct(){
      parent::__construct($this->model);
  }

  public function adminLetterHistory(Request $request){

    //Agnt
    $agent = (Auth::user()->role < 4) ? User::where('id',Auth::user()->id)->with('agent')->first()->agent->id : false;    
    $letters = new Letter;
    if($agent){
      $letters = $letters->whereHas('toUser.girl.agent',function($q) use($agent) {
        $q->where('id',$agent);
      });
    } 

    //Period
    if(isset($request->search)){
      $search = json_decode($request->search);
      $dates = [
        'from' => $search->periodFrom,
        'to'   => $search->periodTo
      ];
      $letters = $letters->where(function($q)use($dates) {
                                  $q->where('created_at', '>=', $dates['from'])
                                  ->where('created_at', '<=', $dates['to']);
                                });
    }
    $letters = $letters
                  ->whereHas('user.man')                      
                  ->whereHas('toUser.girl')                      
                  ->orderBy('created_at','desc')
                  ->with('user.man')
                  ->with('toUser.girl')
                  ->with('toUser.girl.agent')
                  ->paginate(10);         

    //Get answers
    $lettersWithAnswers = [];
    $got = [];
    foreach ($letters as $k => $v) {      
      $answers = Letter::where('created_at', '>',$v->created_at)
                    ->where('user_id', $v->to_user_id)
                    ->where('to_user_id', $v->user_id)
                    ->get();

      $lwa = $v->toArray();           
      $lwa['answers'] = [];

      //add answer
      foreach ($answers as  $a) {
        if(array_search($a->id, $got)) continue;
        array_push($got,$a->id);
        array_push($lwa['answers'],$a);
      }              

      array_push($lettersWithAnswers,$lwa);
    }

    return response()->json(['error' => '0', 'data' => ['letters' => $lettersWithAnswers, 'pages' => $letters->lastPage()]]);
  }


    public function _index(){
      // //Get Model
      // $model = new $this->model();
      // if(Auth::User()->role == 3){  

      //   $id =  Auth::User()->agent->id; 
      //   $callback = function($q)use($id) {
      //     $q->where('id','=',$id);
      //   };        
      //   $dbData = letter::with('user.man')
      //                 ->with('user.girl')
      //                 ->with(['user.girl.agent' => $callback])
      //                 ->with('toUser.man')
      //                 ->with(['toUser.girl.agent' => $callback])                      
      //                 ->whereHas('user.girl.agent' , $callback)
      //                 ->orWhereHas('toUser.girl.agent' , $callback)
      //                 ->orderBy('created_at','DESC')
      //                 ->paginate(50);
      // }else{
      //   $dbData = letter::with('user.man')
      //                 ->with('user.girl')
      //                 ->with('user.girl.agent')
      //                 ->with('toUser.man')
      //                 ->with('toUser.girl.agent')
      //                 ->orderBy('created_at','DESC')
      //                 ->paginate(50);        
      // }


      // //Get Data
      // $data   = $model->getData($dbData); // Data
      // $inputs = $model->getInputs();  //Inputs
      // $names   = $model->getNames();  //Names
      // $page   = $model->getPage();    //Page
      // $route = $model->getRoute(); 
      // $settings = $model->getSettings(); 


      // //Encode
      // $data   = json_encode($data);
      // $inputs = json_encode($inputs);
      // $names   = json_encode($names);
      // $route   = json_encode($route);
      // $settings   = json_encode($settings);

      // return view($page)
      //   ->with('data', $data)
      //   ->with('inputs', $inputs)
      //   ->with('name', $names)
      //   ->with('route',$route)
      //   ->with('settings',$settings);


      $data['search'] = [
        ['name'=>'period',
        'type'=>'fromToDate',
        'fromDef'=>Carbon::now()->subMonth(2)->format('Y-m-d'),
        'toDef'=>Carbon::now()->format('Y-m-d'),
        'fromName'=>'periodFrom',
        'toName'=>'periodTo'],      
      ];

      return view('admin.pages.vue')
        ->with('vue', 'admin-letter-history')
        ->with('data', json_encode($data));
    }

    public function index(){

      $user = User::getWithInfo(Auth::User()->id);

      //View
      return view('pages.letters')->with('user', json_encode($user));
    }

    public function put(Request $request){

        //Get Model
        $model = new $this->model; 
        
        $inputs =  Input::all();

        //Validate
        $model->validate($request);
        if(!isset($inputs['user_id'])){
          $inputs['user_id'] = Auth::user()->id;
        }     

        //correct user
        if(Auth::user()->id <> $inputs['user_id']){
          if(User::getWithInfo(Auth::User()->id)['man'] < 3)
            return response()->json(['error' => '1', 'text' => 'bad user']);
        }

        $id = $model::sendLetter([
            'subject'     => $inputs['subject'],
            'body'        => $inputs['body'],
            'user_id'     => $inputs['user_id'],
            'to_user_id'  => $inputs['to_user_id'],
        ]);


        //Save
        if($id){
            return response()->json(['error' => '0', 'id' => $id]);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function getCompanions(Request $request){
      $userId = $request->userId;

      if($userId == NULL){
        $userId = Auth::User()->id;
      }

      $companions = $this->model::getCompanions($userId);


      return response()->json(['error' => 0, 'data' => $companions]);
    }

    public function getSingleCompanion(Request $request){

      $user = User::getWithInfo(Auth::User()->id);
      //Get companion
      if(isset($request->id)){
        $companion = User::getWithInfo($request->id);
      }else{
         return response()->json(['error' => 1]);
      }

      //Genre check
      if($companion['man'] == $user['man']) return response()->json(['error' => 2]);

      //role check
      if($companion['role'] == 0 && $user['man'] == false) return response()->json(['error' => 3]);

      return response()->json(['error' => 0, 'data' => $companion]);
    }

    public function getLetters(Request $request){

      $userId = $request->userId;

      if($userId == NULL){
        $userId = Auth::User()->id;
      }

      $letters = $this->model::getLetters($userId,$request->companionId);

      return response()->json(['error' => 0, 'data' => $letters]);
    }

    public function getUserLetters($userId = NULL){

      //@@@ middleware


      if($userId == NULL){
        $userId = Auth::User()->id;
      }

      return response()->json(['error' => 0, 'data' => $this->model::getUserLetters($userId)]);
    }

    public function read(Request $request){

      //Validation
      $request->validate([
          'user_id'=> new CurrentUserOrAdmin
      ]);

      Letter::where('user_id',$request->companion_id)
            ->where('to_user_id',$request->user_id)
            ->whereNULL('read')
            ->update(['read' => now()]);


      return response()->json(['error' => '0']);
    }

    public function payLetter(Request $request){

      $letter_id = $request->id;

      //Check alredy payed
      if(LetterPay::where('letter_id','=',$letter_id)->exists()){
        return response()->json(['error' => '1', 'text' => 'Already payed!']);
      }

      //Get Letter
      $letter = Letter::find($letter_id);

      //Get User
      $user = User::with('man')->find($letter->to_user_id);

      //Check man
      if(!$user->man()->exists()){
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!']);
      }      

      //Get Membership
      $membership = Membership::getCurrentMembership($user->id);
      if(!$membership){
        return response()->json(['error' => '1', 'text' => 'No membership!']);
      }

      //Get Letter price
      $price = Letter::getLetterCost($letter->body, $membership);

      //Check balance
      $balance = $user->man->balance;
      $newBalance = $balance - $price;
      if($newBalance < 0){
        return response()->json(['error' => '1', 'text' => 'Not enought balance!']);
      }

      //Store pay
      try {

        DB::beginTransaction();

        //Store pay
        $letterPayId = LetterPay::create([
          'letter_id' => $letter_id,
          'price'     => $price
        ])->id;
  
        //Edit balance
        $user->man->balance = $newBalance;
        $user->man->save();

        $order = new Order;
        $order->user_id      = $user->id;
        $order->name         = Letter::getLetterType($letter_id);
        $order->category     = 'letters';
        $order->product_id   = $letter_id;
        $order->method       = 'inner';
        $order->transaction  = $membership->name;
        $order->status_id    =  1;
        $order->value        =  $price - ($price*2) ;

        $order->save();


        //Store to DB
        DB::commit();

       } catch (Exception $e) {
        // Rollback from DB
        DB::rollback();
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
      }

      //Return
      return response()->json(['error' => '0', 'id' => $letterPayId, 'letter' => $letter]);
    }

    //Admin
    public function getAdminGirls(){

      //Validate
      $user = User::getWithInfo(Auth::User()->id);

      //User
      if($user['man'] < 3) 
        return response()->json(['error' => 1, 'text' => 'bad user']);

      $model = new Girl();

      // Agent
      if($user['man'] == 3) {
        $girls = Agent::where('user_id','=',$user['id'])->with('girl')->first()->toArray();
        if(!isset($girls['girl']))
          $girls = [];
        else
          $girls = $girls['girl'];
      }

      //Admin
      if($user['man'] == 4) {

        //get
        // $girls = User::with('girl')
        //               ->whereHas('inLetter', function ($query) {
        //                   $query->where('read','IS','NULL');
        //                   $query->orderBy('created_at', 'desc');
        //               })
        //               ->take(5)
        //               ->get();


        // dd($girls->toArray());

        
        // $columns = [
        //   [
        //     'name'    => 'photo',
        //     'file'    => 'image',
        //   ],
        //   ['name' => 'id'],
        //   ['name' => 'name'],
        //   ['name' => 'birth'],
        // ];
        // $model->setColumns($columns);
        // $model->setPerPage(20);

        // $where = [['column' => 'read','condition' => 'IS','value' => 'NULL']];
        // $model->setWhere($where);

        $data = $model->getData();
      }

      $girls = $data['data'];

      return response()->json(['error' => '0', 'data' => $girls]);
    }

    //Admin panel
    public function getLongLetterLength(){
      return response()->json(['error' => '0', 'length' => Letter::getLongLetterLength()]);
    }

    public function postLongLetterLength(Request $request){

      //@@@ validate

      $letterSize = $request->letterSize;

      if(!Letter::setLongLetterLength($letterSize)){
        return response()->json(['error' => '1']);
      }

      return response()->json(['error' => '0']);
    }



}