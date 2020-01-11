<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceCategoryController extends _adminPanelController
{
    protected $model     = 'App\ServiceCategory';

    public function __construct(){
        parent::__construct($this->model);
    }
}
