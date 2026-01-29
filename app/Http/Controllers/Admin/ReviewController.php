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

        $reviews = $query->latest('created_at')->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->approve();

        notify()->success()
            ->title(__('common.Approved'))
            ->message(__('common.Review approved successfully'))
            ->send();

        return back();
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        notify()->success()
            ->title(__('common.Deleted'))
            ->message(__('common.Review deleted successfully'))
            ->send();

        return back();
    }
}
