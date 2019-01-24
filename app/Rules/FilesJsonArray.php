<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilesJsonArray implements Rule
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
        // Decode
        $arr = json_decode($value);

        // Is array
        return is_array($arr);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute files error(1)';
    }
}
