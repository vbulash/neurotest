<?php

    namespace App\Http\Requests;

    use App\Rules\INNRule;
    use App\Rules\OGRNControlSumRule;
    use App\Rules\OGRNRule;
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
                'inn' => [
                    'bail',
                    'required',
                    'unique:clients,inn',
                    new INNRule($this)
                ],
                'ogrn' => [
                    'bail',
                    'required',
                    'unique:clients,ogrn',
                    new OGRNRule($this),
                    new OGRNControlSumRule($this)
                ],
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
