<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilesJsonMax implements Rule
{

    private $max;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max)
    {
        $this->max = $max;
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

        return $this->max >= count($arr);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Maximum :attribute - '.$this->max;
    }
}
