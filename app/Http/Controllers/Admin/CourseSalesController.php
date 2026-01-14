<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePurchase;
use Illuminate\View\View;

class CourseSalesController extends Controller
{
    public function index(): View
    {
        $purchases = CoursePurchase::with(['course.subject', 'course.teacher', 'student', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('status', 'succeeded');
            })
            ->latest('purchased_at')
            ->paginate(20);

        $totalRevenue = CoursePurchase::whereHas('payment', function ($query) {
            $query->where('status', 'succeeded');
        })
            ->with('payment')
            ->get()
            ->sum(fn ($purchase) => $purchase->payment->amount ?? 0);

        $totalSales = $purchases->total();

        return view('admin.course-sales.index', compact('purchases', 'totalRevenue', 'totalSales'));
    }
}
