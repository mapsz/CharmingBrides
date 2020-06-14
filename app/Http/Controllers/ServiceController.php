<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\User;
use App\ServiceCategory;
use App\Order;
use App\Letter;
use App\OrderComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends _adminPanelController
{
    protected $model     = 'App\Service';


    public function index($id){


      return view('pages.vue')->with('vue','service')->with('data',$id);
    }

    public function allIndex(){
      return view('pages.vue')->with('vue','all-services');
    }

    public function get($id){

      $columns = [
        [
          'name'    => 'image',
          'file'    => 'image',
        ],
        ['name' => 'id'],
        ['name' => 'name'],
        ['name' => 'description'],
        ['name' => 'price'],
      ];

      $model = new $this->model;
      $model->setSingleId($id);
      $model->setColumns($columns);
      $data   = $model->getData()['data'][0];

      return response()->json(['error' => '0','data' => json_encode($data)]);
    }

    public function getAll(){
      $columns = [
        [
          'name'    => 'image',
          'file'    => 'image',
        ],
        ['name' => 'id'],
        ['name' => 'name'],
        ['name' => 'description'],
        ['name' => 'price'],
      ];


      $model = new $this->model;   
      $model->setColumns($columns);    
      $services   = $model->getData();

      //Categories
      $columns = [
        [
          'name'    => 'image',
          'file'    => 'image',
        ],
        ['name' => 'id'],
        ['name' => 'name'],
        ['name' => 'description'],
      ];


      $model = new ServiceCategory;
      $model->setColumns($columns);    
      $categories   = $model->getData();
    

      $data = [
        'services' => $services,
        'categories' => $categories,
      ];

      return response()->json(['error' => '0','data' => json_encode($data)]);      
    }

    public function getMenu(){
      $services = Service::where('menu','=',1)->get();
      $categories = ServiceCategory::where('menu','=',1)->get();

      $data = [
        'services' => $services,
        'categories' => $categories,
      ];

      return response()->json(['error' => '0','data' => json_encode($data)]);
    }

    public function categoryIndex($id){
      return view('pages.vue')->with('vue','service-category')->with('data',$id);
    }

     public function getCategoryServices($id){

      $columns = [
        [
          'name'    => 'image',
          'file'    => 'image',
        ],
        ['name' => 'id'],
        ['name' => 'name'],
        ['name' => 'description'],
        ['name' => 'price'],
      ];      

      $model = new $this->model;
      $model->setColumns($columns);
      $model->setCustomQueries(
        $model->whereHas('category', function($q)use($id){
          $q->where('id', '=', $id);
        })
      );

      $data['services']  = $model->getData()['data'];
      $data['name']  = ServiceCategory::where('id','=',$id)->first()->name;

      return response()->json(['error' => '0','data' => json_encode($data)]);

     }

    public function buyService(Request $request){

      //Data 
      $serviceId = $request->id;
      $userId = Auth()->user()->id;

      //Service
      $service = Service::find($serviceId);

      //User
      $user = User::where('id',$userId)->with('man')->first();

      //Insufficient funds!
      if($user->man->balance < $service->price)
        return response()->json(['error' => '0','data' => 2]);

      //Buy
      //Store pay
      try {

        DB::beginTransaction();
  
        //Edit balance
        $user->man->balance -= $service->price;
        $user->man->save();

        $order = new Order;
        $order->user_id      = $userId;
        $order->name         = $service->name;
        $order->category     = 'Service';
        $order->product_id   = $serviceId;
        $order->method       = 'inner';
        $order->status_id    =  1;
        $order->value        =  $service->price - ($service->price*2) ;

        $order->save();

        if(isset($request->comment) && isset($request->comment) != ""){
          $comment = new OrderComment;
          $comment->order_id      = $order->id;
          $comment->comment       = $request->comment;
  
          $comment->save();
        }

        //Store to DB
        DB::commit();

       } catch (Exception $e) {
        // Rollback from DB
        DB::rollback();
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
      }      

      return response()->json(['error' => '0','data' => 1]);
    }

    public function __construct(){
        parent::__construct($this->model);
    }
}
