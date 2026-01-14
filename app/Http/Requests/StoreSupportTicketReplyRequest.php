<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
            'is_internal' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Message is required.',
            'message.max' => 'Message cannot exceed 5000 characters.',
        ];
    }
}
