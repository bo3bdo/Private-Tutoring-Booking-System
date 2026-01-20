<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TeacherProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function show(TeacherProfile $teacher): View
    {
        $teacher->load([
            'user',
            'subjects',
            'defaultLocation',
            'availabilities',
        ]);

        $reviews = $teacher->getAllReviews();
        $averageRating = $teacher->averageRating();
        $reviewsCount = $teacher->reviewsCount();

        // Get teacher's courses
        $courses = Course::where('teacher_id', $teacher->user_id)
            ->where('is_published', true)
            ->with(['subject', 'lessons'])
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Get upcoming available slots
        $upcomingSlots = $teacher->timeSlots()
            ->where('status', 'available')
            ->where('start_at', '>=', now())
            ->where('end_at', '>', now()) // Only show slots that haven't ended yet
            ->orderBy('start_at')
            ->limit(10)
            ->get();

        return view('student.teachers.show', compact(
            'teacher',
            'reviews',
            'averageRating',
            'reviewsCount',
            'courses',
            'upcomingSlots'
        ));
    }

    public function reviews(TeacherProfile $teacher): JsonResponse
    {
        $reviews = $teacher->getAllReviews();

        return response()->json([
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
                'average_rating' => $teacher->averageRating(),
                'reviews_count' => $teacher->reviewsCount(),
            ],
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name,
                    'created_at' => $review->created_at->format('M j, Y'),
                ];
            }),
        ]);
    }
}
