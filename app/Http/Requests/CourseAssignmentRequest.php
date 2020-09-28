<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course' => ['required', 'string', Rule::exists('courses', 'slug')]
        ];
    }

    public function getCourse()
    {
        return Course::whereSlug($this->course)->first();
    }
}
