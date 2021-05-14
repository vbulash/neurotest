<?php

namespace App\Rules;

use App\Models\FMPType;
use App\Models\Neuroprofile;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class ProfileCodeRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $profiles = Neuroprofile::all()->where('fmptype_id', $this->request->fmptype);
        if (!$profiles) return true;

        $profiles = $profiles->where('code', $value);
        if (!$profiles) return true;
        if (count($profiles) == 0) return true;
        if (count($profiles) > 1) return false;

        if ($this->request->has('id'))
            if ($profiles->first()->id == $this->request->id)
                return true;

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Нейропрофиль с кодом &laquo;:input&raquo; уже существует';
    }
}
