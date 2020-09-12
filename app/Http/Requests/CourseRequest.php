<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'unique:courses,name'],
            'description' => ['required', 'string'],
            'source' => ['required', 'string', 'url'],
            'employee_level_id' => ['required', 'integer', 'exists:employee_levels,id']
        ];
    }
}
