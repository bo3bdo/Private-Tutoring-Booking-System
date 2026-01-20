<?php

namespace App\Http\Requests\Teacher;

use App\Enums\MeetingProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTeacher();
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string', 'max:1000'],
            'hourly_rate' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'supports_online' => ['nullable', 'boolean'],
            'supports_in_person' => ['nullable', 'boolean'],
            'default_location_id' => ['nullable', 'exists:locations,id'],
            'default_meeting_provider' => ['nullable', Rule::enum(MeetingProvider::class)],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['exists:subjects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.max' => 'Bio must not exceed 1000 characters.',
            'hourly_rate.required' => 'Hourly rate is required.',
            'hourly_rate.numeric' => 'Hourly rate must be a number.',
            'hourly_rate.min' => 'Hourly rate must be at least 0.',
            'hourly_rate.max' => 'Hourly rate must not exceed 9999.99.',
            'default_location_id.exists' => 'Selected location does not exist.',
            'subjects.required' => 'Please select at least one subject.',
            'subjects.min' => 'Please select at least one subject.',
            'subjects.*.exists' => 'One or more selected subjects do not exist.',
        ];
    }
}
