<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseSalesController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('viewSales', $course);

        $enrollments = $course->enrollments()
            ->with(['student'])
            ->latest('enrolled_at')
            ->paginate(20);

        $totalRevenue = $course->purchases()
            ->whereHas('payment', function ($query) {
                $query->where('status', 'succeeded');
            })
            ->with('payment')
            ->get()
            ->sum(fn ($purchase) => $purchase->payment->amount ?? 0);

        $totalEnrollments = $enrollments->total();

        return view('teacher.courses.sales', compact('course', 'enrollments', 'totalRevenue', 'totalEnrollments'));
    }
}
