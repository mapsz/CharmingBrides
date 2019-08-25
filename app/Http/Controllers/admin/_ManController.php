<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Man;
use App\Membership;

class ManController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	//Get men fro DB
        $menDB = Man::with('user')->get();

        //Form men for front end
        $men = [];
        foreach ($menDB as $manDB) {
        	array_push($men, 
        		[
        			'id'	=> $manDB->user->id,
              'name'  => $manDB->name,
        			'balance'  => $manDB->balance,
        		]
        	);
        }

        return view('admin.pages.men')->with('men',json_encode($men));

    }

    public function attachMembership(Request $request){

        //Validate
        $this->validate($request,[
            'userId'              => 'required|exists:users,id',
            'membershipId'        => 'required|exists:memberships,id',
        ]);       

        //Attach
        $membership = Membership::find($request->membershipId);
        $membership->user()->attach($request->userId);

        if(!$membership->save())
            return response()->json(['error' => '1', 'text' => 'Somethink gone wrong']);
            
        //Add Balance
        //Get user
        $user = User::with('man')->find($request->userId);

        //Get man
        $man = Man::find($user->man->id);
        $man->balance = $man->balance + $membership->price;

        if(!$man->save())
            return response()->json(['error' => '1', 'text' => 'Somethink gone wrong']);
        

        return response()->json(['error' => '0', 'membership' => $membership, 'balance' => $man->balance]);
    }
}
