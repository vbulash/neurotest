<?php

namespace App\Http\Requests;

use App\Rules\RoleNameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO Сделать более жесткое правило
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
            'name' => new RoleNameRule($this),
            'permissions' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Наименование роли',
            'permissions' => 'Разрешения роли'
        ];
    }
}
