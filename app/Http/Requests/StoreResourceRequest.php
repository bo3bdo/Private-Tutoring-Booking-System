<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTeacher() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'resourceable_type' => ['required', 'string', Rule::in(['App\Models\Booking', 'App\Models\Course', 'App\Models\CourseLesson'])],
            'resourceable_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => ['required', 'file', 'max:51200'], // 50MB max
            'is_public' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'resourceable_type.required' => 'Resource type is required.',
            'resourceable_type.in' => 'Invalid resource type.',
            'resourceable_id.required' => 'Resource item is required.',
            'title.required' => 'Title is required.',
            'file.required' => 'File is required.',
            'file.max' => 'File size must not exceed 50MB.',
        ];
    }
}
