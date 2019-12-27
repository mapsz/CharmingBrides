<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;

class LogController extends Controller
{
  public function log(Request $reques){

    $l = new Log();

    // dd($reques);

    if(isset($reques->code)) $l->code = $reques->code;      
    if(isset($reques->text)) $l->text = json_encode($reques->text);

    $l->save();

    return response()->json(['error' => 0]);

  }
}
