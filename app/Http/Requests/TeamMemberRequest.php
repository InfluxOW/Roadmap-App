<?php

namespace App\Http\Requests;

use App\Models\UserTypes\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee' => ['required', 'string', Rule::exists('users', 'username')->where('role', 'employee')]
        ];
    }

    public function getEmployee()
    {
        return Employee::whereUsername($this->employee)->first();
    }
}
