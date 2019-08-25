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

    public function __construct(){
        parent::__construct($this->model);
    }

}
