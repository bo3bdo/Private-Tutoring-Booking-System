<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
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
