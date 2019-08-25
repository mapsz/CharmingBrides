<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Membership;
use App\User;
use Illuminate\Support\Facades\Auth;

class MembershipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$memberships = Membership::all();

        return view('admin.pages.memberships')->with('memberships',json_encode($memberships));

    }
    public function get()
    {
        $memberships = Membership::all();

        return response()->json(['error' => false, 'data' => $memberships]);

    }
    public function create()
    {
        return view('admin.pages.createMembership');
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'name'              => 'required|unique:memberships',
            'price'             => 'required|numeric',
            'letter_price'      => 'required|numeric',
            'chat_price'        => 'required|numeric',
            'period'            => 'required|integer',
            'client_visible'    => 'required|boolean',
        ]);       

        $membership = New Membership;

        $membership->name = $request->name;
        $membership->price = $request->price;
        $membership->letter_price = $request->letter_price;
        $membership->chat_price = $request->chat_price;
        $membership->period = $request->period;
        $membership->client_visible = $request->client_visible;

        if($membership->save())
            return response()->json(['error' => '0', 'id' => $membership->id]);
        else
            return response()->json(['error' => '1', 'text' => 'Somethink gone wrong']);
    }




}
