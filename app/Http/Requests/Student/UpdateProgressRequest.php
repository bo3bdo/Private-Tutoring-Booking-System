<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isStudent();
    }

    public function rules(): array
    {
        return [
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'watched_seconds.integer' => 'Watched seconds must be a number.',
            'watched_seconds.min' => 'Watched seconds cannot be negative.',
        ];
    }
}
