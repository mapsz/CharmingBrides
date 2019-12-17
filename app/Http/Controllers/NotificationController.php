<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notification;
use App\User;

class NotificationController extends Controller
{

  public function index(){
    return view('pages.vue')->with('vue','men-notifications');
                              // ->with('data',json_encode($data));
  }

  public function get(){

    //User
    $id = AUth::User()->id;
    if(!$id) 
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    $noty = (new Notification)->get($id);
    if(!$noty)
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    return response()->json(['error' => '0','data' => $noty]);
  }

  public function post(Request $request){
    //Noty
    if(!isset($request->noty)) 
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    //User
    $id = AUth::User()->id;
    if(!$id) 
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    //Post noty
    $noty = (new Notification)->post($id, $request->noty);
    if(!$noty)
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);


    return response()->json(['error' => '0']);
  }

  public function sets(){

    if(Auth::User())
      $id = Auth::User()->id;
    else
      return response()->json(['error' => '0','data' => true]);

    if(Auth::User()->role != 2)
      return response()->json(['error' => '0','data' => true]);

    if(!$id)
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    if(Notification::where('user_id',$id)->count() > 0){
      return response()->json(['error' => '0','data' => true]);
    }else{
      return response()->json(['error' => '0','data' => -1]);
    }  
  }

  public function verified(){

    $user = Auth::User();

    if($user)
      $id = $user->id;
    else
      return response()->json(['error' => '0','data' => true]);

    if($user->role != 2)
      return response()->json(['error' => '0','data' => true]);

    if(!$id)
      return response()->json(['error' => '1', 'text' => 'something gone wrong']);

    if($user->hasVerifiedEmail()){
      return response()->json(['error' => '0','data' => true]);
    }else{
      return response()->json(['error' => '0','data' => -1]);
    } 
  }

}



    // return response()->json(['error' => '1', 'text' => 'something gone wrong']);
    // return response()->json(['error' => '0','data' => $data]);