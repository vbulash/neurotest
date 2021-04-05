<?php

namespace App\Http\Requests;

use App\Models\Block;
use App\Rules\BlockImageRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateBlockRequest extends FormRequest
{
    protected $handler;
    protected array $rules = [];
    protected array $names = [];

    protected function init(): void {
        $block = Block::findOrFail($this->block_id);
        $this->handler = config('blocks.' . $block->type);

        $this->rules = ['title' => 'required'];
        $this->names = ['title' => 'Наименование модуля'];

        $handler = $this->handler;
        foreach ($handler::$content as $control) {
            if($control['required']) {
                switch ($control['type']) {
                    case 'image':
                        $rule = 'nullable|image';
                        break;
                    default:
                        $rule = 'required';
                        break;
                }
                $this->rules[$control['name']] = $rule;
                $this->names[$control['name']] = $control['label'];
            }
        }
        Log::debug(print_r($this->rules, true));
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
        if(!$this->rules) $this->init();
        return $this->rules;
    }

    public function attributes()
    {
        if(!$this->names) $this->init();
        return $this->names;
    }
}
