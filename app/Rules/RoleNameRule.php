<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleNameRule implements Rule
{
    private Request $request;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        Log::debug('attribute = ' . $attribute);
        Log::debug('value = ' . $value);
        if(in_array($attribute, [Role::ADMIN, Role::MANAGER, Role::MANAGER, Role::CLIENT])) return true;
        if($value) return true;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
