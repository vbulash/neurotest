<?php

namespace App\Rules;

use App\Models\License;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class LicenseCount implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $newFreeCount = intval($value);
        $existingCount = intval(License::all()->where('contract_id', $this->request->contract_id)->count());
        return $newFreeCount >= $existingCount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Новое количество лицензий должно быть не меньше количества уже выпущенных';
    }
}
