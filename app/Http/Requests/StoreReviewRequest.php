<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reviewable_type' => ['required', 'string', Rule::in(['App\Models\Booking', 'App\Models\Course', 'App\Models\TeacherProfile'])],
            'reviewable_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reviewable_type.required' => 'Review type is required.',
            'reviewable_type.in' => 'Invalid review type.',
            'reviewable_id.required' => 'Review item is required.',
            'rating.required' => 'Rating is required.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'comment.max' => 'Comment cannot exceed 2000 characters.',
        ];
    }
}
