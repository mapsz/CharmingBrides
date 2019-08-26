<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Man;

class ManController extends _adminPanelController
{
  
    protected $model     = 'App\Man';

    public function __construct(){
        parent::__construct($this->model);
    }

    public function index($id){

      $man = User::where('id',$id)->with('man')->First()->toArray();

      return view('pages.man')->with('man',json_encode($man));
    }


    public function create(){
        //Get Model
        $model = new $this->model();
        //Get Data
        $inputs = $model->getInputs();  //Inputs
        $route = $model->getRoute();        
        $route['prefix'] = "";
        //Encode
        $inputs     = json_encode($inputs);
        $route      = json_encode($route);

        //View
        return view('pages.registrationMan')
                    ->with('inputs', $inputs)
                    ->with('route',$route);    
    }

    public function put(Request $request){
      //
       //Get Model
      $model = new $this->model;       
      //Validate
      $validate = $model->validate($request); 
      //Save
      $man_id = $model->saveRow($request->all());
      if($man_id){
        $user_id = Man::find($man_id)->user_id;
        $user = User::find($user_id);
        Auth::login($user);
        return response()->json(['error' => '0','id' => $user_id]);
      }else{
        return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      }      
    }

    public function edit(Man $man){
        $men['email'] = Auth::user()->email;
        return view('pages.profile')->with('men',$men);
    }
}
