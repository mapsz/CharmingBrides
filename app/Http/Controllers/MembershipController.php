<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipController extends _adminPanelController
{

    protected $model     = 'App\Membership';

    public function __construct(){
        parent::__construct($this->model);
    }

}
