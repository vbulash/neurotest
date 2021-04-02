<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreClientRequest extends FormRequest
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
                'name' => 'required',
                'inn' => 'required|unique:clients,inn',
                'ogrn' => 'required|unique:clients,ogrn',
                'address' => 'required'
            ];
        }

        public function attributes()
        {
            return [
                'name' => 'Наименование',
                'inn' => 'ИНН',
                'ogrn' => 'ОГРН',
                'address' => 'Адрес'
            ];
        }
    }
