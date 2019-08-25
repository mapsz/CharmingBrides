<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Man;
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

    public function getCurrent(Request $request){

        //Validation
        $request->validate([
            'user_id'=> new CurrentUserOrAdmin
        ]);

        //Get membership
        $membership  = Membership::getCurrentMembership($request->user_id);

        //Encode
        if($membership)
          $membership = json_encode($membership->toArray());

        return response()->json(['error' => 0, 'data' => $membership]);
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

      // Get user
      $user = User::find($request->user_id);
      // Get man
      $man = DB::table('men')->where('user_id','=',$request->user_id)->get()[0];
      $man = Man::find($man->id);

      //Get membership price
      $price = Membership::find($request->membership_id)->price;

      try {
        DB::beginTransaction();
        //Attach membership
        
        $user->membership()->attach($request->membership_id);

        //Add balance
        $man->balance += $price;
        $man->save();

        DB::commit();     
      } catch (Exception $e) {
        DB::rollback();
        return response()->json(['error' => 1]);
      }

      return response()->json(['error' => 0]); 
    }

}
