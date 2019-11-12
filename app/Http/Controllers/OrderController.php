<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends _adminPanelController
{

    protected $model     = 'App\Order';

    public function __construct(){
        parent::__construct($this->model);
    }

}
