<?php

namespace ArtisanCloud\SaaSFramework\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AccountExists implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !empty(User::findByPhone($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'account not exists';
    }
}
