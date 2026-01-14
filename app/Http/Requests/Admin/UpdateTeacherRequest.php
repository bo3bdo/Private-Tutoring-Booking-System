<?php

namespace App\Http\Requests\Admin;

use App\Enums\MeetingProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $userId = $teacher->user_id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'hourly_rate.numeric' => 'Hourly rate must be a number.',
            'default_location_id.exists' => 'Selected location does not exist.',
            'subjects.*.exists' => 'One or more selected subjects do not exist.',
        ];
    }
}
