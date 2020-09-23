<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PresetGenerationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'technologies' => ['required', 'array'],
            'technologies.*' => [
                'required',
                'distinct',
                'string',
                Rule::exists('technologies', 'name'),
            ],
            'name' => ['required', 'string', Rule::unique('presets', 'name')->where('manager_id', $this->user()->id)],
            'description' => ['required', 'string'],
        ];
    }
}
