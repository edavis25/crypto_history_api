<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsPositiveInteger implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // cytpe_digit determines if EVERY value in string is numeric
        // aka only integers: no floats, etc.
        if (ctype_digit($value)) {
            return ((int) $value) > 0;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be a positive integer.';
    }
}
