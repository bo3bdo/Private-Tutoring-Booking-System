<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacherProfile;

        // Get booking IDs for this teacher
        $bookingIds = $teacher->bookings()->pluck('id');

        // Get course IDs for this teacher
        $courseIds = Course::where('teacher_id', $teacher->user_id)->pluck('id');

        // Build query for all reviews
        $query = Review::where('is_approved', true)
            ->where(function ($q) use ($teacher, $bookingIds, $courseIds) {
                // Direct teacher profile reviews
                $q->where(function ($subQ) use ($teacher) {
                    $subQ->where('reviewable_type', \App\Models\TeacherProfile::class)
                        ->where('reviewable_id', $teacher->id);
                })
                // Booking reviews
                    ->orWhere(function ($subQ) use ($bookingIds) {
                        if ($bookingIds->isNotEmpty()) {
                            $subQ->where('reviewable_type', Booking::class)
                                ->whereIn('reviewable_id', $bookingIds);
                        }
                    })
                // Course reviews
                    ->orWhere(function ($subQ) use ($courseIds) {
                        if ($courseIds->isNotEmpty()) {
                            $subQ->where('reviewable_type', Course::class)
                                ->whereIn('reviewable_id', $courseIds);
                        }
                    });
            })
            ->with('user');

        // Filter by review type
        if ($request->has('type') && $request->type !== '') {
            $type = $request->type;
            $query->where(function ($q) use ($type, $teacher, $bookingIds, $courseIds) {
                if ($type === 'teacher') {
                    $q->where('reviewable_type', \App\Models\TeacherProfile::class)
                        ->where('reviewable_id', $teacher->id);
                } elseif ($type === 'booking' && $bookingIds->isNotEmpty()) {
                    $q->where('reviewable_type', Booking::class)
                        ->whereIn('reviewable_id', $bookingIds);
                } elseif ($type === 'course' && $courseIds->isNotEmpty()) {
                    $q->where('reviewable_type', Course::class)
                        ->whereIn('reviewable_id', $courseIds);
                }
            });
        }

        // Search in comments
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $query->latest()->paginate(15);

        // Calculate statistics
        $averageRating = $teacher->averageRating();
        $reviewsCount = $teacher->reviewsCount();

        return view('teacher.reviews.index', compact('reviews', 'averageRating', 'reviewsCount'));
    }
}
