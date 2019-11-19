<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\User;
use App\Man;
use App\Order;

class ManController extends _adminPanelController
{
  
    protected $model     = 'App\Man';

    public function __construct(){
        parent::__construct($this->model);
    }

    public function loginAdminMan(request $request){


      $user = User::find($request->user_id);
      Auth::login($user);


      return response()->json(['error' => '0', 'text' => $request->user_id]);

    }

    public function _index(){
      if(Auth::User()->role == 3){

        //Get Model
        $model = new $this->model();

        $model->setColumns([
                  [
                    'name' => 'id',
                    'relation' => 'user.id'
                  ],
                  ['name' => 'favorite'],
                  ['name' => 'name'],
                  ['name' => 'country'],
                  [
                    'name' => 'birth',
                    'caption' => 'age',
                    'timeFormat'  => 'age'
                  ], 
                  [
                    'name' => 'created_at',
                    'timeFormat'  => 'j M Y G:i'       
                  ]
                ]  
              );  

        //settings 
        $settings = [
          'add' => false,
          'edit' => false,
          'delete' => false,
        ];

        $model->setSettings($settings);

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
        ['name' => 'name'],
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

      if(Auth::User()->role == 2 || Auth::User()->role == 4){
        array_push($columns, [
          'name' => 'user_id',
          'caption' => 'email',
          'relation' => 'user.email',
        ]);

        array_push($columns, ['name' => 'surname']);
      }
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

    public function editBalance(request $request){

      if(!$request->user_id) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      if(!isset($request->add)) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      if(!isset($request->balance)) return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      
      $balance = $request->balance;
      if($request->add == 0){
        $balance -= $balance * 2;
      }

      $man = Man::where('user_id',$request->user_id)->first();

      $value = $man->balance + $balance;

      try {

        DB::beginTransaction();

        //Store pay
        Man::where('user_id',$man->id)
          ->update(['balance' => $value]);

        //save order
        $order = new Order;
        $order->user_id      = $request->user_id;
        $order->name         = 'Admin Balance Edit';
        $order->category     = 'admin';
        $order->product_id   = 0;
        $order->method       = 'admin';
        $order->status_id    = 1;
        $order->value        = $balance;
        $order->save();

        //Store to DB
        DB::commit();

       } catch (Exception $e) {
        // Rollback from DB
        DB::rollback();
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
      }

      return response()->json(['error' => '0']);
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
      $rows = $request->all();
      $man_id = $model->saveRow($rows);
      if(!$man_id) return response()->json(['error' => '2', 'text' => 'something gone wrong']);
      $user = User::find($man_id);
      $user->role = 2;
      if($user->save()){
        Auth::login($user);
        return response()->json(['error' => '0','id' => $man_id]);
      }else{
        return response()->json(['error' => '1', 'text' => 'something gone wrong']);
      }      
    }

    public function getMenParametr(){

      $params = [];
      //Locations
      $q = DB::select( DB::raw(
                   "SELECT country, COUNT(*)
                    FROM men
                    GROUP BY country
                    HAVING COUNT(*) > 1"
                  ));

      $param = [];
      foreach ($q as $key => $v) {
        array_push($param, $v->country);
      }
      $params['country'] = $param;


      return response()->json(['error' => '0','data' => $params]);
    }

    public function search(request $request){

      $model = new $this->model();

      //Set up
      $model->setPerPage(20);
      // $model->setInfoColumns();
      $model->setOrder(['row'=>'id','order'=>'DESC']);

      if(!isset($request->search)) return response()->json(['error' => '1']);

      // decode
      $search = json_decode($request->search);

      //where
      $where = [];

      //counry
      if(isset($search->counry) && $search->counry != '0'){
        array_push($where, ['column' => 'counry','condition' => '=','value' => $search->counry]);
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
      if(isset($search->search)){
        $val = $search->search;
        $model->setCustomQueries([
          $model->where(function($q)use($val) {
            $q->where('name','LIKE','%'.$val.'%')
              ->orWhere('id','LIKE','%'.$val.'%');
          })
        ]);
      }

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
      $data = ['men' => $girls, 'settings' => $settings];
      return response()->json(['error' => '0','data' => $data]);      
    }

    public function _search(request $request){

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

      //counry
      if(isset($search->counry) && $search->counry != '0'){
        array_push($where, ['column' => 'location','condition' => '=','value' => $search->location]);
      }

      //favorite
      if(isset($search->favorites) && $search->favorites == true){
        array_push($where, ['column' => 'favorite','condition' => '=','value' => 1]);
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
        if(Auth::user()->role == 4){
           $custom = $custom->where(function($q)use($val) {
            $q->where('name','LIKE','%'.$val.'%')
              ->orWhere('user_id','LIKE','%'.$val.'%')
              ->orWhere('surname','LIKE','%'.$val.'%')
              ->orWhereHas('user', function($qu)use($val){
                            $qu->where('email','LIKE','%'.$val.'%');
                          });
          });         
        }
        if(Auth::user()->role == 3){
            $custom = $custom->where(function($q)use($val) {
            $q->where('name','LIKE','%'.$val.'%')
              ->orWhere('user_id','LIKE','%'.$val.'%');
          });      
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

}
