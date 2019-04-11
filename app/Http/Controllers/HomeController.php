<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

Use App\ChatHistory;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ChatHistory::chatDisconnect('private-privateChat.54');
        return view('home');
    }
}
