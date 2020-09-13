<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:presets,name'],
            'description' => ['required', 'string'],
        ];
    }
}
