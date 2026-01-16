<?php

namespace App\Http\Requests\Admin;

use App\Enums\MeetingProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'is_active' => ['nullable', 'boolean'],
            'supports_online' => ['nullable', 'boolean'],
            'supports_in_person' => ['nullable', 'boolean'],
            'default_location_id' => ['nullable', 'exists:locations,id'],
            'default_meeting_provider' => ['nullable', Rule::enum(MeetingProvider::class)],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Teacher name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'hourly_rate.numeric' => 'Hourly rate must be a number.',
            'default_location_id.exists' => 'Selected location does not exist.',
            'subjects.*.exists' => 'One or more selected subjects do not exist.',
        ];
    }
}
