<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;
use Illuminate\Support\Facades\Auth;

class CurrentUserOrAdmin implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //Check user is admin
        if(!Auth::user()->role > 2){
            if(Auth::user()->id != $value) return false;
        } 
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Bad user.';
    }
}
