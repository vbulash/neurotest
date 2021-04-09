<?php

namespace App\Http\Requests;

use App\Models\QuestionSet;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    protected int $quantity = 2;

    public function __construct()
    {
        parent::__construct();

        $set_id = session('set_id');
        $set = QuestionSet::findOrFail($set_id);
        $this->quantity = $set->quantity;
    }


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
        switch($this->quantity) {
            case QuestionSet::IMAGES2:
                return [
                    'imageA' => 'required|image',
                    'imageB' => 'required|image'
                ];
            case QuestionSet::IMAGES4:
                return [
                    'imageA' => 'required|image',
                    'imageB' => 'required|image',
                    'imageC' => 'required|image',
                    'imageD' => 'required|image'
                ];
            default:
                return [];
        }
    }

    public function attributes()
    {
        switch($this->quantity) {
            case QuestionSet::IMAGES2:
                return [
                    'imageA' => 'Изображение А',
                    'imageB' => 'Изображение Б'
                ];
            case QuestionSet::IMAGES4:
                return [
                    'imageA' => 'Изображение А',
                    'imageB' => 'Изображение Б',
                    'imageC' => 'Изображение В',
                    'imageD' => 'Изображение Г'
                ];
            default:
                return [];
        }
    }
}
