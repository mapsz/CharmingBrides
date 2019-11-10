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
      $model = new $this->model();

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
        [
          'name' => 'user_id',
          'caption' => 'email',
          'relation' => 'user.email',
        ], 
        ['name' => 'name'],
        ['name' => 'surname'],
        ['name' => 'birth'],
        ['name' => 'country'],
        ['name' => 'city'],
        ['name' => 'height'],
        ['name' => 'weight'],
        ['name' => 'smoking'],
        ['name' => 'alcohol'],
        ['name' => 'education'],
        ['name' => 'profession'],
        ['name' => 'maritial'],
        ['name' => 'children'],
        ['name' => 'info'],
        //Girl
        ['name' => 'preffer_from'],
        ['name' => 'preffer_to'],
        ['name' => 'girl_hair'],
        ['name' => 'girl_height_from'],
        ['name' => 'girl_height_to'],
        ['name' => 'girl_weight_from'],
        ['name' => 'girl_weight_to'],
        ['name' => 'girl_smoking'],
        ['name' => 'girl_alcohol'],
        ['name' => 'girl_education'],
        ['name' => 'girl_proffesion'],
        ['name' => 'girl_maritial'],
        ['name' => 'girl_children'],
        ['name' => 'girl_info'],        
      ];
      $model->setColumns($columns);

      //Set where
      $where = [['column' => 'user_id','condition' => '=','value' => $id]];
      $model->setWhere($where);    

      //Get  
      $data   = $model->getData();
      $data   = $data['data'][0];

      foreach ($data as $k => $v) {
        $data[$k] = $this->model::getMoreInfo($k,$v);
      }     

      $data = json_encode($data);
      return view('pages.profile')->with('data',$data)->with('id',$id);
    }

    public function profile(){
      $model = new $this->model();

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
        [
          'name' => 'user_id',
          'caption' => 'email',
          'relation' => 'user.email',
        ], 
        ['name' => 'name'],
        ['name' => 'surname'],
        ['name' => 'birth'],
        ['name' => 'country'],
        ['name' => 'city'],
        ['name' => 'height'],
        ['name' => 'weight'],
        ['name' => 'smoking'],
        ['name' => 'alcohol'],
        ['name' => 'education'],
        ['name' => 'profession'],
        ['name' => 'maritial'],
        ['name' => 'children'],
        ['name' => 'info'],
        //Girl
        ['name' => 'preffer_from'],
        ['name' => 'preffer_to'],
        ['name' => 'girl_hair'],
        ['name' => 'girl_height_from'],
        ['name' => 'girl_height_to'],
        ['name' => 'girl_weight_from'],
        ['name' => 'girl_weight_to'],
        ['name' => 'girl_smoking'],
        ['name' => 'girl_alcohol'],
        ['name' => 'girl_education'],
        ['name' => 'girl_proffesion'],
        ['name' => 'girl_maritial'],
        ['name' => 'girl_children'],
        ['name' => 'girl_info'],        
      ];
      $model->setColumns($columns);

      //Set where
      $where = [['column' => 'user_id','condition' => '=','value' => Auth::User()->id]];
      $model->setWhere($where);    

      //Get  
      $data   = $model->getData();
      $data   = $data['data'][0];

      foreach ($data as $k => $v) {
        $data[$k] = $this->model::getMoreInfo($k,$v);
      }     

      $data = json_encode($data);
      return view('pages.profile')->with('data',$data);
    }

    public function profileMembership(){
      return view('pages.profileMembership');
    }

    public function edit(){
      //Get Model
      $model = new $this->model();

      //Set single row      
      $model->setSingleId(User::with('man')->find(Auth::user()->id)->man->id);
      //prepare data
      $model->getData();

      //Get Data
      $inputs     = $model->getInputs();  //Inputs
      $names      = $model->getNames();  //Names
      // $route      = $model->getRoute(); 
      $settings   = $model->getSettings(); 
      $editData   = $model->getEditData();

      // dd($route);
      $route =  [
        "prefix" => "",
        "r" => "profile"
      ];   

      //Encode
      $inputs     = json_encode($inputs);
      $names      = json_encode($names);
      $route      = json_encode($route);
      $settings   = json_encode($settings);
      $editData   = json_encode($editData);

      //View
      return view('pages.manEdit')
                  ->with('inputs', $inputs)
                  ->with('editData', $editData)
                  ->with('name', $names)
                  ->with('route',$route)
                  ->with('settings',$settings);
    }

    public function post(Request $request){
      return $this->_post($request);
    }


    public function create(){
        //Get Model
        $model = new $this->model();

        //Set inputs
        $inputs = [
          [ //Email
            'name'      => 'email', 
            'parent'    => 'User',
            'type'      => 'email',
            'example'   => 'michael.smith@gmail.com'
          ],
          [ //Password
            'name'      => 'password',
            'type'      => 'password',
            'parent'    => 'User',
          ],    
          [ //Password confirm
            'name'      => 'confirm_password',
            'caption'   => 'Confirm Password',
            'type'      => 'password',
            'parent'    => 'User',
            'confirm'   => true,
            'hash'      => false,
          ],  
          [ //Name
            'name' => 'name',
            'type' => 'text',
            'example' => 'Michael'
          ],
          [ //Surname
            'name' => 'surname',
            'type' => 'text',
            'example' => 'Smith',
            'required' => false,
          ],
          [ //Birth
            'name' => 'birth',
            'type' => 'date',
            'example' => '05/32/1980',
          ],  
        ];        
        $model->setInputs($inputs);  //Inputs

        //Get Data
        $inputs = $model->getInputs($inputs);  //Inputs
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
      //Get Model
      $model = new $this->model;
      //Validate
      $model->validate($request); 
      //Save
      $man_id = $model->saveRow($request->all());
      if(!$man_id) return response()->json(['error' => '2', 'text' => 'something gone wrong']);
      $user = User::find($man_id);
      if($user){
        Auth::login($user);
        return response()->json(['error' => '0','id' => $man_id]);
      }else{
        return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      }      
    }
}
