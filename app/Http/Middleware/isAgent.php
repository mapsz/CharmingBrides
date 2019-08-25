<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class IsAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //Is admin
        if (Auth::user() &&  Auth::user()->role == 4) {
           return $next($request);
        }


        //Is agent
        $user = User::getWithInfo(Auth::user()->id);
        if ($user['man'] == 3) {
           return $next($request);
        }



        return redirect('/');
    }


}
