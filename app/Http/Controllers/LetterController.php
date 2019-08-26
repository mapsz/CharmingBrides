<?php

namespace App\Http\Controllers;

use App\Letter;
use App\LetterPay;
use App\User;
use App\Membership;
use App\Agent;
use App\Girl;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Illuminate\Support\Facades\Config;

class LetterController extends _adminPanelController
{

    protected $model     = 'App\Letter';

    public function __construct(){
        parent::__construct($this->model);
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

        $put = new $model;
        foreach ($inputs as $k => $v) {
          $put->$k = $v;
        }

        //Save
        if($put->save()){
            return response()->json(['error' => '0', 'id' => $put->id]);
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
    public function getGirls(){

      //Validate
      $user = User::getWithInfo(Auth::User()->id);

      //User
      if($user['man'] < 3) 
        return response()->json(['error' => 1, 'text' => 'bad user']);

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
        $girls = User::has('girl')
                      ->where('role',1)
                      ->with('girl')
                      ->get()->toArray();
      }

      //Set array
      $girlsArray = [];
      foreach ($girls as $k => $v) {
        $girlsArray[$k]['id']     = $v['id'];
        $girlsArray[$k]['man']    = false;
        $girlsArray[$k]['name']   = $v['girl']['name'];
      }

      return response()->json(['error' => '0', 'data' => $girlsArray]);

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