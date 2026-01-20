<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Review;
use App\Models\TeacherProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $student = auth()->user();

        // Get all reviews written by this student
        $query = $student->reviews()->with('reviewable');

        // Filter by review type
        if ($request->has('type') && $request->type !== '') {
            $type = $request->type;
            $query->where(function ($q) use ($type) {
                if ($type === 'booking') {
                    $q->where('reviewable_type', Booking::class);
                } elseif ($type === 'course') {
                    $q->where('reviewable_type', Course::class);
                } elseif ($type === 'teacher') {
                    $q->where('reviewable_type', TeacherProfile::class);
                }
            });
        }

        // Search in comments
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('comment', 'like', "%{$search}%");
        }

        $reviews = $query->latest()->paginate(15);

        // Calculate statistics
        $totalReviews = $student->reviews()->count();
        $averageRating = $student->reviews()->avg('rating') ?? 0;

        return view('student.reviews.index', compact('reviews', 'totalReviews', 'averageRating'));
    }

    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $reviewable = match ($request->reviewable_type) {
            'App\Models\Booking' => Booking::findOrFail($request->reviewable_id),
            'App\Models\Course' => Course::findOrFail($request->reviewable_id),
            default => null,
        };

        if (! $reviewable) {
            notify()->error()
                ->title(__('common.Error'))
                ->message(__('common.Invalid review item'))
                ->send();

            return back();
        }

        // Check if user already reviewed this item
        $existingReview = Review::where('user_id', auth()->id())
            ->where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->first();

        if ($existingReview) {
            notify()->warning()
                ->title(__('common.Already reviewed'))
                ->message(__('common.You have already reviewed this item'))
                ->send();

            return back();
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id' => $request->reviewable_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-approve reviews
            'approved_at' => now(),
        ]);

        notify()->success()
            ->title(__('common.Sent'))
            ->message(__('common.Review submitted successfully'))
            ->send();

        return back();
    }
}
