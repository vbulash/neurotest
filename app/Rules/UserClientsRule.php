<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;

class UserClientsRule implements Rule
{
    private FormRequest $request;

    /**
     * Create a new rule instance.
     *
     * @param FormRequest $request
     */
    public function __construct(FormRequest $request)
    {
        $this->request = $request;
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
        if($value == null) return true;

        //$wildcards = Auth::user()->roles()->where('wildcards', '1')->get()->first();
        $roles = $this->request->roles;
        $wildcards = Role::all()->whereIn('id', $roles)->where('wildcards', '1')->toArray();
        //Log::debug('Wildcards = ' . ($wildcards == null ? 'Нет' : 'Да'));
        return $wildcards !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Текущий набор ролей не позволяет иметь индвидуальный доступ к конкретным клиентам (только ко всем клиентам).<br>" .
            "Нет необходимости указывать отдельных клиентов";
    }
}
