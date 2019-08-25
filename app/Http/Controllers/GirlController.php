<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialLady;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GirlController extends _adminPanelController
{
  
    protected $model     = 'App\Girl';

    public function __construct(){
        parent::__construct($this->model);
    }


    public function index($id)
    {

      $girl = User::where('id',$id)->with('girl')->First()->toArray();

      //Girl not found
      if(!$girl){
        return redirect('/');
      }


      //Remove $girl['girl']
      $g = $girl['girl'];
      $g['id'] = $girl['id'];
      $g['role'] = $girl['role'];
      $g['genre_id'] = $girl['girl']['id'];
      $girl = $g;
      $girl['age'] = Carbon::parse($girl['birth'])->age;


      //Is user      
      if(Auth::check()){
        $user = User::getWithInfo(Auth::user());   
        if ($user['man'] < 3) {
          //User
          //girl unconfirm
          if($girl['role'] == 0) return redirect('/');
            
          //Unset non user data
          unset($girl['email']);
          unset($girl['email_verified_at']);
          unset($girl['role']);
          unset($girl['forAdminName']);
          unset($girl['forAdminSurname']);
          unset($girl['forAdminFathersName']);
          unset($girl['forAdminPhoneNumber']);
          unset($girl['firstLetter']);
          unset($girl['firstLetterSubject']); 
          //Remove $girl['girl']

        }else{
          //Admin

          //@@@ только своих баб агенту

        }
      }else{
        $user['man'] = 0;

        //non auth info
        $g = [];
        $g['id']        = $girl['id'];   
        $g['location']  = $girl['location'];
        $g['name']      = $girl['name'];
        $g['age']       = $girl['age'];

        $girl = $g;
      }


      //More info decode
      foreach ($girl as $k => $v) {
        $girl[$k] = $this->model::getMoreInfo($k,$v);
      }          


      // dd($girl);

      return view('pages.girl')->with('girl',json_encode($girl))->with('auth',Auth::check())->with('userIsMan',$user['man']);
    }    

    public function getSpecialLadies(){

        $girls = SpecialLady::with('user')->with('user.girl')->get()->toArray();

        $data = [];
        $r = [];
        foreach ($girls as $key => $value) {
            $r['id']      = $value['user']['id'];
            $r['name']    = $value['user']['girl']['name'];
            $r['location']= $value['user']['girl']['location'];
            $r['birth']   = $value['user']['girl']['birth']; 
            $r['age']     = Carbon::parse($r['birth'])->age;

            array_push($data, $r);
        }

        return response()->json(['error' => '0', 'data' => $data]);
    }

    public function deleteSpecialLadies(request $request){
        $delete = SpecialLady::where('user_id','=',$request->id);
        // dd($delete);
        $id = $delete->delete();
        return response()->json(['error' => '0', 'id' => $id]);  
    }

    public function putSpecialLadies(request $request){
        //Check max specials
        $maxSpecials = 8;
        $currentSpecials = SpecialLady::count();
        if(($maxSpecials - $currentSpecials) <= 0){
            return response()->json(['error' => '1', 'text' => 'Max speacial ladies - '.$maxSpecials]);
        }

        $put = new SpecialLady;

        $put->timestamps = false;
        $put->user_id = $request->id;       

        //Save
        if($put->save()){
            return response()->json(['error' => '0']);
        }else{
            return response()->json(['error' => '1', 'text' => 'something gone wrong']);
        }
    }

    public function confirm(request $request){

      $user = User::find($request->id);

      if(!$user) return response()->json(['error' => '1', 'text' => 'something gone wrong']);

      if($request->confirm)
        $user->role = 0;
      else
        $user->role = 1;

      if(!$user->save()) return response()->json(['error' => '1', 'text' => 'something gone wrong']);

      return response()->json(['error' => '0']);

    }
    
}
