<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

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
