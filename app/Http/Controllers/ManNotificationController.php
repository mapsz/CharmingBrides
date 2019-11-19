<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ManNotification;

class ManNotificationController extends Controller
{
  public function get(){
    return response()->json(['error' => '0', 'data' => ManNotification::get()]);
  }
}
