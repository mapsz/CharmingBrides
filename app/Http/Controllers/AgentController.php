<?php

namespace App\Http\Controllers;

use App\User;
use App\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AgentController extends _adminPanelController
{

    protected $model     = 'App\Agent';


    public function _put(Request $request){
        //Get Model
        $model = new $this->model; 
        
        //Validate
        $validate = $model->validate($request); 

        $add = $request->all();
        $add['role'] = 3;

        //Save
        $agentId = $model->saveRow($add);
        if(!$agentId) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        //Set role
        $user = User::find($agentId);
        $user->role = 3;
        if($user->save()){
            return response()->json(['error' => '0', 'id' => $agentId]);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }


    public function __construct(){
        parent::__construct($this->model);
    }

}
