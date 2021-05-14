<?php

namespace App\Http\Requests;

use App\Rules\ProfileCodeRule;
use Illuminate\Foundation\Http\FormRequest;

class NeuroprofileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'required',
                new ProfileCodeRule($this)
            ],
            'name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
          'code' => 'Код нейропрофиля',
          'name' => 'Наименование'
        ];
    }
}
