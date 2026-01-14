<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['nullable', 'string', Rule::in(['low', 'medium', 'high', 'urgent'])],
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Subject is required.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description cannot exceed 5000 characters.',
            'priority.in' => 'Invalid priority level.',
        ];
    }
}
