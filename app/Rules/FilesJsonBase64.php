<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilesJsonBase64 implements Rule
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

        foreach ($arr as $v) {
            // Cut 
            $v = substr ($v,strpos($v,'base64,')+7);
            // Check
            if ( base64_encode(base64_decode($v, true)) !== $v){
                return false;
            }           
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
        return ':attribute files error(2)';
    }
}
