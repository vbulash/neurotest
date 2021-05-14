<?php

    namespace App\Http\Requests;

    use App\Models\Block;
    use Illuminate\Foundation\Http\FormRequest;

    class BlockRequest extends FormRequest
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
            $rules = [
                'description' => 'required'
            ];
            switch ($this->kind) {
                case Block::TYPE_TEXT:
                case Block::TYPE_SCRIPT:
                    $rules['content'] = 'required';
                    break;
                case Block::TYPE_IMAGE:
                    $rules['image'] = 'required|image';
                    break;
                case Block::TYPE_VIDEO:
                    $rules['clip'] = 'required';
            }
            return $rules;
        }

        public function attributes()
        {
            $attr = [
                'description' => 'Наименование'
            ];
            switch ($this->kind) {
                case Block::TYPE_TEXT:
                    $attr['content'] = 'Полный текст';
                    break;
                case Block::TYPE_IMAGE:
                    $attr['image'] = 'Изображение';
                    break;
                case Block::TYPE_SCRIPT:
                    $attr['content'] = 'Скрипт';
                    break;
                case Block::TYPE_VIDEO:
                    $attr['clip'] = 'Видео';
                    break;
            }
            return $attr;
        }
    }
