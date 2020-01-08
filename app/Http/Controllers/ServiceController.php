<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends _adminPanelController
{
    protected $model     = 'App\Service';


    public function index($id){


      return view('pages.vue')->with('vue','service')->with('data',$id);
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


      $model = new $this->model();
      $model->setSingleId($id);      
      $model->setColumns($columns);    
      $data   = $model->getData()['data'][0];  

      return response()->json(['error' => '0','data' => json_encode($data)]);
    }


    public function __construct(){
        parent::__construct($this->model);
    }
}
