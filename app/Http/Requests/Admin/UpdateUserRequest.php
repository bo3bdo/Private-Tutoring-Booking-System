<?php

namespace App\Http\Requests\Admin;

use App\Enums\MeetingProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $user = $this->route('user');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['teacher', 'student'])],
        ];

        // If role is teacher, add teacher profile rules
        if ($this->input('role') === 'teacher') {
            $rules = array_merge($rules, [
                'bio' => ['nullable', 'string', 'max:1000'],
                'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
                'is_active' => ['nullable', 'boolean'],
                'supports_online' => ['nullable', 'boolean'],
                'supports_in_person' => ['nullable', 'boolean'],
                'default_location_id' => ['nullable', 'exists:locations,id'],
                'default_meeting_provider' => ['nullable', Rule::enum(MeetingProvider::class)],
                'subjects' => ['nullable', 'array'],
                'subjects.*' => ['exists:subjects,id'],
            ]);
        }

        // If role is student, add student profile rules
        if ($this->input('role') === 'student') {
            $rules = array_merge($rules, [
                'phone' => ['nullable', 'string', 'max:255'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('common.Name is required'),
            'email.required' => __('common.Email is required'),
            'email.email' => __('common.Please provide a valid email address'),
            'email.unique' => __('common.This email is already registered'),
            'password.min' => __('common.Password must be at least 8 characters'),
            'password.confirmed' => __('common.Password confirmation does not match'),
            'role.required' => __('common.Role is required'),
            'role.in' => __('common.Invalid role selected'),
            'hourly_rate.numeric' => __('common.Hourly rate must be a number'),
            'default_location_id.exists' => __('common.Selected location does not exist'),
            'subjects.*.exists' => __('common.One or more selected subjects do not exist'),
        ];
    }
}
