<?php

namespace App\Http\Controllers;

use App\Man;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;

class ManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pages.registrationMan');

        // ->with('girls',$girls)
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|min:2',
            'surname' => 'required|string|min:2',
        ]);

        // Store
        try {

            DB::beginTransaction();

            //Prepare User
            $user = User::create(['email' => $request->email,
                                'password' => Hash::make($request->password),
                                'role' => 2,
                            ]);
            //Prepare man
            $manData = [
                'user_id'   => $user->id,
                'name'      => $request->name,
                'surname'   => $request->surname,
            ];
            Man::create($manData);

            //Store to DB
            DB::commit();

         } catch (Exception $e) {
            // Rollback from DB
            DB::rollback();
            return redirect()->back()->with('errors', ['error register!']);
        }

        Auth::login($user);
        return redirect()->route('profile');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Man  $man
     * @return \Illuminate\Http\Response
     */
    public function show(Man $man)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Man  $man
     * @return \Illuminate\Http\Response
     */
    public function edit(Man $man)
    {
        //
        // dd(Auth::user());
        $men['email'] = Auth::user()->email;
        return view('pages.profile')->with('men',$men);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Man  $man
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Man $man)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Man  $man
     * @return \Illuminate\Http\Response
     */
    public function destroy(Man $man)
    {
        //
    }
}
