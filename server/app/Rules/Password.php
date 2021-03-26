<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
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
        // NOTE regex /^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*\d)(?=.*\W]).*$/
        // NOTE \W is similar with character set [-+_!@#$%^&*.,?]
        $pattern = '/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[-+_!@#$%^&*.,?]).*$/';

        return preg_match($pattern, $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.strong_password');
    }
}
