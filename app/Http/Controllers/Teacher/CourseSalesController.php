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

        // Use database aggregation instead of loading all records
        $totalRevenue = $course->purchases()
            ->join('payments', 'course_purchases.payment_id', '=', 'payments.id')
            ->where('payments.status', 'succeeded')
            ->sum('payments.amount');

        $totalEnrollments = $enrollments->total();

        return view('teacher.courses.sales', compact('course', 'enrollments', 'totalRevenue', 'totalEnrollments'));
    }
}
