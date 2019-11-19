<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ManFavorite;
use Illuminate\Support\Facades\Auth;

class ManFavoriteController extends Controller
{
  public function put(request $request){


    if(!Auth::check()) return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
    if(Auth::User()->role != 2) return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
    if(!isset($request->girl_user_id)) return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);

    if(ManFavorite::where('man_user_id',Auth::User()->id)->where('girl_user_id',$request->girl_user_id)->count() > 0)
      return response()->json(['error' => '1', 'text' => 'Favorite already exists!']);

    if(!ManFavorite::put($request->girl_user_id)) return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
     
    return response()->json(['error' => '0']);

  }
}
