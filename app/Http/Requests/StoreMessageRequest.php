<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Verify user belongs to the conversation
        $conversationId = $this->input('conversation_id');

        if (! $conversationId) {
            return false;
        }

        $conversation = \App\Models\Conversation::find($conversationId);

        if (! $conversation) {
            return false;
        }

        // Check if authenticated user is either user_one or user_two in the conversation
        $user = $this->user();

        return $user && ($conversation->user_one_id === $user->id || $conversation->user_two_id === $user->id);
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
