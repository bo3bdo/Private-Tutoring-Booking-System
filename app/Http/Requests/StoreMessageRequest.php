<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => ['required', 'exists:conversations,id'],
            'body' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240'], // 10MB max per file
        ];
    }

    public function messages(): array
    {
        return [
            'conversation_id.required' => 'Conversation is required.',
            'conversation_id.exists' => 'Conversation does not exist.',
            'body.required' => 'Message body is required.',
            'body.max' => 'Message cannot exceed 5000 characters.',
            'attachments.max' => 'You can attach up to 5 files.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
        ];
    }
}
