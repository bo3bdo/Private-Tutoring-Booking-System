<?php

namespace App\Http\Requests\Student;

use App\Enums\MeetingProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent() && ! $this->user()->isTeacher();
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string', 'max:1000'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'qualifications' => ['required', 'string', 'max:2000'],
            'experience' => ['required', 'string', 'max:2000'],
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
            'qualifications.required' => __('common.Qualifications are required'),
            'qualifications.max' => __('common.Qualifications must not exceed 2000 characters'),
            'experience.required' => __('common.Experience is required'),
            'experience.max' => __('common.Experience must not exceed 2000 characters'),
            'hourly_rate.numeric' => __('common.Hourly rate must be a number'),
            'hourly_rate.min' => __('common.Hourly rate must be at least 0'),
            'hourly_rate.max' => __('common.Hourly rate must not exceed 9999.99'),
            'default_location_id.exists' => __('common.Selected location does not exist'),
            'subjects.required' => __('common.Please select at least one subject'),
            'subjects.min' => __('common.Please select at least one subject'),
            'subjects.*.exists' => __('common.One or more selected subjects do not exist'),
        ];
    }
}
