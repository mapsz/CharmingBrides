<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Image;

class FilesJsonMaxResolution implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->width = config('media.maxSize');;
        $this->height = config('media.maxSize');;
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
            // Read img
            $img = Image::make($v); 
            // Check
            if($img->width() > $this->width || $img->height()> $this->height)  return false;
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
        return 'Maximum :attribute width - '.$this->width. ', height - '.$this->height;
    }
}
