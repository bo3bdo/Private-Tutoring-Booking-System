<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['user', 'reviewable']);

        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->approve();

        return back()->with('success', 'Review approved successfully.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
