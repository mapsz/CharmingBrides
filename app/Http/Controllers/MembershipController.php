<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Man;
use App\Order;
use Illuminate\Support\Facades\Auth;
use App\Membership;
use App\Rules\CurrentUserOrAdmin;
use Illuminate\Support\Facades\DB;

class MembershipController extends _adminPanelController
{

    protected $model     = 'App\Membership';

    public function __construct(){
        parent::__construct($this->model);
    }

    public function index(){
      return view('pages.membership');
              // ->with('man',json_encode($man));
    }

    public function getMemberships(){
      // $memberships = Membership::where('client_visible','=','1')->get()->toArray();

      // return response()->json(['error' => 0, 'data' => $memberships]);

      //Get Model
      $model = new $this->model();

      //Settings  
      $where = [['column' => 'client_visible','condition' => '=','value' => '1']];
      $model->setWhere($where);
      $columns = $model->getColumns();
      array_push($columns,[
        'name'    => 'image',
        'file'    => 'image',
      ]);
      $model->setColumns($columns);

      //Get Data
      $data   = $model->getData();       

      return response()->json(['error' => '0', 'data' => $data]);

    }

    public function getCurrent(Request $request){

      if(!isset($request->user_id)){
        $auth = Auth::user();
        if(!$auth) return response()->json(['error' => 2]);
        $request->user_id = $auth->id;
      }

      //Validation
      $request->validate([
          'user_id'=> new CurrentUserOrAdmin
      ]);

      //Get membership
      $membership  = Membership::getCurrentMembership($request->user_id);

      //Encode
      if($membership){
        //add photo
        $model = new $this->model;
        $files = $model->getFiles($membership->id,'image');
        $membership->image=$files;        
      }
      else{
        return response()->json(['error' => 1]);
      }

      return response()->json(['error' => 0, 'data' => json_encode($membership->toArray())]);
    }

    public function getHistory(Request $request){
      
      //Validation
      $request->validate([
          'user_id'=> new CurrentUserOrAdmin
      ]);

      $memberships = User::with('membership')->find($request->user_id);

      $membershipData = $memberships->membership->sortByDesc('pivot.created_at')->toArray();
      $membershipDataFormated = [];

      $i = 0;
      foreach ($membershipData as $k => $v) {
        $membershipDataFormated[$i]['name']     = $v['name'];
        $membershipDataFormated[$i]['price']    = $v['price'];
        $membershipDataFormated[$i]['buyDate']  = $v['pivot']['created_at'];
        // $membershipDataFormated[$i]['expires']  = $v['created_at'];
        $i++;
      }

      // dd($membershipDataFormated);

      $data = [
        'columns' => [
          [ //Name
            'name' => 'name',
            'caption' => 'name'
          ],
          [ //Price
            'name' => 'price',
            'caption' => 'price'
          ],
          [ //Buy date
            'name' => 'buyDate',
            'caption' => 'buy date'
          ],
          // [ //Expires  //@@@
          //   'name' => 'expires',
          //   'caption' => 'expires'
          // ],

        ],
        'data' => $membershipDataFormated,
      ];



      return response()->json(['error' => 0, 'data' => $data]); 
    }

    public function attachMembership(Request $request){

      if(!$request->user_id || !$request->membership_id) 
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);

      //Store pay
      try {

        DB::beginTransaction();


        $membership = Membership::where('id',$request->membership_id)->first();
        if(!$membership) return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);

        Membership::attachMembership($request->user_id, $membership->id);
  
        //save order
        $order = new Order;
        $order->user_id      = $request->user_id;
        $order->name         = $membership->name;
        $order->category     = 'membership';
        $order->product_id   = $membership->id;
        $order->method       = 'admin';
        $order->status_id    =  1;
        $order->value        =  $membership->price;
        $order->save();

        //Store to DB
        DB::commit();

       } catch (Exception $e) {
        // Rollback from DB
        DB::rollback();
        return response()->json(['error' => '1', 'text' => 'Something gone wrong!!']);
      }

      return response()->json(['error' => 0]); 
    }

}
