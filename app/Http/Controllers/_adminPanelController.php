<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class _adminPanelController extends Controller
{
  	protected $model;

  	public function __construct($model) {
  		$this->model 	= $model;
   	}

    public function index(){
        //Get Model
        $model = new $this->model();

        //Get Data
        $data   = $model->getData(); // Data
        $inputs = $model->getInputs();  //Inputs
        $names   = $model->getNames();  //Names
        $page   = $model->getPage();    //Page
        $route = $model->getRoute(); 
        $settings = $model->getSettings(); 
        if($page == "") $page = 'list';
       
        //Encode
        $data   = json_encode($data);
        $inputs = json_encode($inputs);
        $names   = json_encode($names);
        $route   = json_encode($route);
        $settings   = json_encode($settings);

        return view('_adminPanel.'.$page)
          ->with('data', $data)
          ->with('inputs', $inputs)
          ->with('name', $names)
          ->with('route',$route)
          ->with('settings',$settings);
      }

    public function create(){
        //Get Model
        $model = new $this->model();
        //Get Data
        $inputs = $model->getInputs();  //Inputs
        $names   = $model->getNames();  //Names
        $route = $model->getRoute(); 
        $settings = $model->getSettings(); 
        //Encode
        $inputs     = json_encode($inputs);
        $names      = json_encode($names);
        $route      = json_encode($route);
        $settings   = json_encode($settings);

        //View
        return view('_adminPanel.create')
                    ->with('inputs', $inputs)
                    ->with('name', $names)
                    ->with('route',$route)
                    ->with('settings',$settings);
    }

    public function get(){
        //Get Model
        $model = new $this->model();
        //Get Data
        $data   = $model->getData();
        //Encode
        $data   = json_encode($data);       
        return response()->json(['error' => '0', 'data' => $data]);
    } 

    public function put(Request $request){

        //Get Model
        $model = new $this->model; 
        
        //Validate
        $model->validate($request); 

        //Put
        $put = new $this->model;
        foreach ($model->getInputs() as $key => $value) {
           $put[$value['name']] = $request[$value['name']];
        }

        //Save
        if($put->save()){
            return response()->json(['error' => '0', 'id' => $put->id]);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function post(Request $request){

        //Get Model
        $model = new $this->model; 
        
        //Validate
        $model->validate($request); 

        //post
        $post = $this->model::find($request->id);
        $edit['id'] = $request->id;
        foreach ($model->getInputs() as $key => $value) {
            // dump($value['name'].' - '.$request[$value['name']]);
           $post[$value['name']] = $request[$value['name']];
           $edit[$value['name']]  = $request[$value['name']];
        }
        
        //Save
        if($post->save()){
            return response()->json(['error' => '0', 'edit' => $edit]);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function destroy(Request $request){
        $delete = $this->model::find($request->id);
        $id = $delete->delete();
        return response()->json(['error' => '0', 'id' => $id]);    
    }
}