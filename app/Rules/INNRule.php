<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class INNRule implements Rule
{
    protected Request $request;

    /**
     * Create a new rule instance.
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
        if(strlen($value) != 12) return false;  // Не 12 символов
        if(!is_numeric($value)) return false;   // Не цифры
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Индивидуальный номер налогоплательщика должен состоять из 12 цифр';
    }
}
