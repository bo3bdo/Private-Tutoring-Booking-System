<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTeacher() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'video_provider' => ['required', 'in:url,youtube,vimeo,s3,cloudflare'],
            'video_url' => ['required', 'string', 'max:2000'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'is_free_preview' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Lesson title is required.',
            'video_provider.required' => 'Video provider is required.',
            'video_provider.in' => 'Invalid video provider selected.',
            'video_url.required' => 'Video URL is required.',
            'duration_seconds.integer' => 'Duration must be a number.',
            'duration_seconds.min' => 'Duration cannot be negative.',
        ];
    }
}
