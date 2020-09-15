<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseCompletionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => [Rule::requiredIf(is_null($this->certificate)), 'integer', Rule::in(range(0, 10))],
            'certificate' => [Rule::requiredIf(is_null($this->rating)), 'string', 'url'],
        ];
    }
}
