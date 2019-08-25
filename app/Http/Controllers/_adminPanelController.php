<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class _adminPanelController extends Controller
{
  	protected $model;

  	public function __construct($model) {
  		$this->model 	= $model;
   	}

    public function _index(){
        //Get Model
        $model = new $this->model();

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
    }

    public function _create(){
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

    public function _get(Request $request){

      //Get request
      $id       = (isset($request->id))      ? $request->id       : false;
      $columns  = (isset($request->columns)) ? $request->columns  : false;
      //To array
      if(is_array($columns)){
        foreach ($columns as $k => $v) {
          $columns[$k] = (array)json_decode($v);
        }
      }
      //Get Model
      $model = new $this->model();
      //Settings
      if($id)       $model->setSingleId($id);      
      if($columns)  $model->setColumns($columns);      
      //Get Data
      $data   = $model->getData()['data'];
      if($id)
        $data = $data[0];
      //Encode
      $data   = json_encode($data);       
      return response()->json(['error' => '0', 'data' => $data]);
    } 

    public function _put(Request $request){

        //Get Model
        $model = new $this->model; 
        
        //Validate
        $validate = $model->validate($request); 

        //Save
        $r = $model->saveRow($request->all());

        //response
        if($r){
            return response()->json(['error' => '0', 'id' => $r]);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function _post(Request $request){

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

    public function _destroy(Request $request){

        $model = new $this->model; 

        if($model->deleteRow($request->id)){
          return response()->json(['error' => '0']);
        }
        else{
          return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }      
    }

    public function _attach(Request $request){

        //Get model
        $model = $this->model::find($request->modelId);

        //Validate
        $validate = $model->attachValidate($request);         
        if($validate['error']) return response()->json(['error' => '1', 'text' => $validate['text']]);

        //Attach
        $targetName = $request['targetName'];
        $attach = $model->$targetName();

        //Save
        $save = $attach->attach($request->targetId);

        if($save == null){
          return response()->json(['error' => '0']);
        }else{
          return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }      
    }

    public function _detach(Request $request){
        //@@@
        $target = $request->target;
        $model = $this->model::find($request->model);

        $model->$target()->detach($request->detachId);

        return response()->json(['error' => '0', 'id' => 33]);
    }    

    public function _search(Request $request){

      //Get request data
      $columns = $request->columns;
      $search  = $request->search;

      //Convert to array
      $columns = json_decode($columns);
      foreach ($columns as $k => $v) {
        $columns[$k] = (array)$v;
      }

      //Get Model
      $model = new $this->model();

      //Settings
      $model->setColumns($columns);
      $model->setSearch($search);

      //Get Data
      $data   = $model->getData();       

      return response()->json(['error' => '0', 'data' => $data]);
    }

    public function _getRecent(Request $request){

      //Settings
      $columns = $request->columns;
      $order = ['row' => 'id', 'order' => 'desc'];
      $count = (isset($request->rows)) ? $request->rows : 10 ;    

      //Convert to array
      $columns = json_decode($columns);
      foreach ($columns as $k => $v) {
        $columns[$k] = (array)$v;
      }

      //Get Model
      $model = new $this->model();

      //Settings
      $model->setColumns($columns);
      $model->setOrder($order);
      $model->setCount($count);

      //Get Data
      $data   = $model->getData(); 

      return response()->json(['error' => '0', 'data' => $data]);
    }

    public function _fileUpload(Request $request){

      $file = $request->file('file');

      //Catch file
      $fileEnc = $this->model::cacheFile($file);

      //Response
      if(!$fileEnc)
        //Error
        return response('Could not save file', 500)
                  ->header('Content-Type', 'text/plain');
      else{
        //Success
        return response($fileEnc, 200)
                  ->header('Content-Type', 'text/plain');
      }
    }

    public function test($test){

      $this->model::saveFromCache($test);

      dd();

    }
}