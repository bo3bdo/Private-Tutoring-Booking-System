<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $reviewable = match ($request->reviewable_type) {
            'App\Models\Booking' => Booking::findOrFail($request->reviewable_id),
            'App\Models\Course' => Course::findOrFail($request->reviewable_id),
            default => null,
        };

        if (! $reviewable) {
            return back()->withErrors(['error' => 'Invalid review item.']);
        }

        // Check if user already reviewed this item
        $existingReview = Review::where('user_id', auth()->id())
            ->where('reviewable_type', $request->reviewable_type)
            ->where('reviewable_id', $request->reviewable_id)
            ->first();

        if ($existingReview) {
            return back()->withErrors(['error' => 'You have already reviewed this item.']);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => $request->reviewable_type,
            'reviewable_id' => $request->reviewable_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Requires admin approval
        ]);

        return back()->with('success', 'Review submitted successfully. It will be published after approval.');
    }
}
