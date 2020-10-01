<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FirstAccessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company' => ['required', 'array'],
            'company.website' => ['required', 'string', 'url'],
            'company.name' => ['required', 'string'],
            'company.description' => ['required', 'string'],
            'company.foundation_year' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'company.industry' => ['required', 'string'],
            'company.location' => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')],
        ];
    }
}
