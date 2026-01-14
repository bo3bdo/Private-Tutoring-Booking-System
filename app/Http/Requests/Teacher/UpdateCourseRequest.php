<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTeacher() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $user = $this->user();
        $teacherId = $user->isTeacher() ? $user->teacherProfile?->id : null;

        $rules = [
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'currency' => ['nullable', 'string', 'size:3'],
            'is_published' => ['nullable', 'boolean'],
        ];

        // Validate that teacher teaches this subject (unless admin)
        if ($teacherId && ! $user->isAdmin()) {
            $rules['subject_id'][] = Rule::exists('subject_teacher', 'subject_id')
                ->where('teacher_id', $teacherId);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'Please select a subject.',
            'subject_id.exists' => 'The selected subject does not exist.',
            'title.required' => 'Course title is required.',
            'price.required' => 'Course price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price cannot be negative.',
            'thumbnail.image' => 'Thumbnail must be an image file.',
            'thumbnail.max' => 'Thumbnail size must not exceed 2MB.',
        ];
    }
}
