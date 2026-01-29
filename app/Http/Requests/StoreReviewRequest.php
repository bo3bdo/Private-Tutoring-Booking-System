<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        $reviewableType = $this->input('reviewable_type');
        $reviewableId = $this->input('reviewable_id');

        if (! $reviewableType || ! $reviewableId) {
            return false;
        }

        // Verify user has access to review the resource
        if ($reviewableType === 'App\Models\Booking') {
            $booking = \App\Models\Booking::find($reviewableId);

            if (! $booking) {
                return false;
            }

            // Allow student or teacher of the booking to review
            return $booking->student_id === $user->id || $booking->teacher_id === $user->id;
        }

        if ($reviewableType === 'App\Models\Course') {
            $course = \App\Models\Course::find($reviewableId);

            if (! $course) {
                return false;
            }

            // Allow users to review courses
            // TODO: Add enrollment check for better security
            return true;
        }

        if ($reviewableType === 'App\Models\TeacherProfile') {
            $teacher = \App\Models\TeacherProfile::find($reviewableId);

            if (! $teacher) {
                return false;
            }

            // Allow students who had at least one booking with the teacher to review
            return $user->isStudent() && \App\Models\Booking::where('student_id', $user->id)
                ->where('teacher_id', $teacher->id)
                ->exists();
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'reviewable_type' => ['required', 'string', Rule::in(['App\Models\Booking', 'App\Models\Course', 'App\Models\TeacherProfile'])],
            'reviewable_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'teaching_style_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'communication_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'punctuality_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],
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
