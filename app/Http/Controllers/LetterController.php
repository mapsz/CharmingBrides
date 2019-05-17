<?php

namespace App\Http\Controllers;

use App\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class LetterController extends _adminPanelController
{

    protected $model     = 'App\Letter';

    public function __construct(){
        parent::__construct($this->model);
    }


    public function userIndex(){

        //View
        return view('pages.letter');
    }

    public function getUserLetters($userId = NULL){
        //@@@ middleware


      if($userId == NULL){
        $userId = Auth::User()->id;
      }

      return response()->json($this->model::getUserLetters($userId));

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


}