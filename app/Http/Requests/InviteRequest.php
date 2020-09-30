<?php

namespace App\Http\Requests;

use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('invites', 'email')->where(function (Builder $query) {
                    return $query->where('expires_at', '>', now());
                }),
                Rule::unique('users', 'email'),
            ],
            'role' => ['required', 'string', Rule::in(['manager', 'employee'])],
            'company' => [Rule::requiredIf($this->user()->isAdmin()), 'string', Rule::exists('companies', 'slug')]
        ];
    }
}
