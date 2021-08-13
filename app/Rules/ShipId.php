<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ShipId implements Rule
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
        $sum = (int)explode('-', $value)[0] + (int)explode('-', $value)[1];
        return preg_match('/[1-4]-[1-4]/', $value) && ($sum <= 5);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Wrong ship ID';
    }
}
