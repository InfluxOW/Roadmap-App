<?php

namespace App\Http\Requests;

use App\Models\EmployeeLevel;
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
            'source' => ['required', 'string', 'url', 'unique:courses,source'],
            'level' => ['required', 'string', 'exists:employee_levels,slug']
        ];
    }
}
