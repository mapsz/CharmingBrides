<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;

class Membership extends _adminPanel
{

    //Data
    protected $single    = 'membership';
    protected $multi     = 'memberships';  
    protected $route     = [ 'prefix' => 'admin/' ];
      
    protected $columns  = [
      ['name' => 'id'],
      ['name' => 'name'],
      ['name' => 'price'],
      ['name' => 'letter_price', 'caption' => 'Short Letter'],
      ['name' => 'long_letter_price', 'caption' => 'Long Letter'],
      ['name' => 'chat_price', 'caption' => 'Chat price'],
      ['name' => 'period'],
      [
        'name' => 'client_visible',
        'caption' => 'client visible',
        'attributes' => [
                ['id' => 0,'name' => 'no'],
                ['id' => 1,'name' => 'yes']
           ],
      ],
    ];
    protected $inputs    = [
      [ //Name
        'name' => 'name',
        'type' => 'text',
        'example' => 'Gold Membership'
      ],
      [ //Price
        'name' => 'price',
        'type' => 'text',
        'example' => '35.50',
      ],
      [ //Short Letter price
        'name' => 'letter_price',
        'caption' => 'short letter',
        'type' => 'text',
        'example' => '3.50',
      ],
      [ //Long Letter price
        'name' => 'long_letter_price',
        'caption' => 'long letter',
        'type' => 'text',
        'example' => '4.50',
      ],
      [ //Chat price
        'name' => 'chat_price',
        'caption' => 'chat price',
        'type' => 'text',
        'example' => '1.50',
      ],
      [ // period
        'name' => 'period',
        'type' => 'text',
        'example' => '180',
      ],
      [ //Image
        'name'            => 'image',
        'type'            => 'file',
        'maxFileCount'    => 1,
        'path'            => 'media/memberships',
        'fileName'        => '`id`',
        'maxFileSize'     => '5mb',
        'fileType'        => ['image/*',],  
      ],              
      [ // client visible
        'name' => 'client_visible',
        'caption' => 'Client visible',
        'type' => 'radio',
        'attributes' => [
          ['id' => 0,'name' => 'no'],
          ['id' => 1,'name' => 'yes']
        ],
      ],
    ];

    public function __construct(){
        parent::__construct($this->single, $this->multi, $this->page, $this->inputs);
    }

    // public function validate($request){

    //     $val = $request->validate([
    //         'vehicle_type_id'          => 'required',
    //         'number'                   => 'required|min:3',
    //         'mileage_tracker'         => 'required',
    //         'mileage'                => 'required',
    //     ]);

    //     return $val;
    // }

    public static function attachMembership($userId, $membershipId){

      // Get user
      $user = User::find($userId);
      // Get man
      $man = DB::table('men')->where('user_id','=',$userId)->get()[0];
      $man = Man::find($man->id);

      //Get membership price
      $price = Membership::find($membershipId)->price;

      try {
        DB::beginTransaction();
        //Attach membership
        
        $user->membership()->attach($membershipId);

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
  
    public static function getCurrentMembership($user_id){

    	//Get memberships
      $user = user::where('id','=',$user_id)->with('membership')->with('man')->first();

      //No membership
      if(!isset($user->membership[0])){
         $CurrentMembership = self::where('id','=',1)->first();
         $CurrentMembership->balance = $user->man->balance;
      	return $CurrentMembership;
      }

      //Remove out off date memberships
      $memberships = [];
      $i = 0;
      foreach ($user->membership as $membership) {
      	$endDate = $membership->pivot->created_at->timestamp + $membership->period * 24 * 60 * 60;

      	if ($endDate > now()->timestamp){
      		$memberships[$i] = $membership;
      		$memberships[$i]['endDate'] = $endDate;
      		$i++;
      	}        	
      }

      if(!count($memberships)) return false;

      //Find profitable membership
      // profitable
      $minLetterPrice = 9999;
      foreach ($memberships as $membership){
      	if($membership->letter_price < $minLetterPrice) $minLetterPrice = $membership->letter_price;
      }

      //Remove not profitable memberships
      foreach ($memberships as $k => $membership){
      	if($membership->letter_price > $minLetterPrice) unset($memberships[$k]);
      }

      if(!count($memberships)) return false;

      //Get longest end date membership
      $latestDate = [
      	'latestDate' => 0,
      	'k' => 0,
      ];
      foreach ($memberships as $k => $membership){
      	if($membership->endDate > $latestDate['latestDate']){
      		$latestDate['latestDate'] = $membership->endDate;
      		$latestDate['k'] = $k;
      	}
      }
       
       $CurrentMembership = $memberships[$latestDate['k']];
       $CurrentMembership->balance = $user->man->balance;

       return $CurrentMembership;
    }

    // public static function getChatPrice($userId){
    //     $membership = Membership::getCurrentMembership($userId);
    //     return $membership->chat_price / 60;
    // }


    //Relations
    public function user()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
