<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\ServiceCategory;

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

    public function getCategory($id){

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

      return view('pages.vue')->with('vue','service-category')->with('data',json_encode($data));
    }

    public function __construct(){
        parent::__construct($this->model);
    }
}
