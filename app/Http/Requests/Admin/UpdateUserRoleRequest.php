<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::in(['teacher', 'student'])],
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => __('common.Role is required'),
            'role.in' => __('common.Invalid role selected'),
        ];
    }
}
