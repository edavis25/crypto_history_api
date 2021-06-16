<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsValidSortOrder implements Rule
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
        return in_array(strtoupper($value), ['ASC', 'DESC']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute must be: 'ASC' or 'DESC'.";
    }
}
