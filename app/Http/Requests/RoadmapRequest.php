<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoadmapRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee' => [
                'required',
                'string',
                Rule::exists('users', 'username')->where('role', 'employee')
            ],
            'preset' => ['required', 'exists:presets,slug']
        ];
    }
}
