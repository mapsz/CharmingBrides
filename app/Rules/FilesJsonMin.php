<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilesJsonMin implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min)
    {
        $this->min = $min;
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

        // Remove empty
        $arr = array_filter($arr, function($v){
            if($v == null || $v == "" || $v == false) return false;
            else return true;
        });

        return $this->min <= count($arr);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Minimum :attribute - '.$this->min;
    }
}
