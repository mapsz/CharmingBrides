<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ManFavorite;
use App\Girl;
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

  public function index(){

    if(!Auth::check()) return;
    if(Auth::User()->role != 2) return;

    $favorites = ManFavorite::where('man_user_id',Auth::User()->id)->get();

    $model = new Girl();

    $where = [];
    foreach ($favorites as $k => $fav) {
      if($k == 0) array_push($where, ['column' => 'user_id','condition' => '=','value' => $fav->girl_user_id]);
      else array_push($where, ['column' => 'user_id','condition' => '=','value' => $fav->girl_user_id,'or' => true]);
    }

    $model->setPerPage(999); //@@@
    $model->setInfoColumns();

    $model->setWhere($where);
    $data = $model->getData();
    $data = $data['data'];

    return view('pages.vue')->with('vue','favorite-girls')->with('data',json_encode($data));
  }
}
