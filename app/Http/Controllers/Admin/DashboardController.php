<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index(): View
    {
        $stats = [
            'total_bookings' => \App\Models\Booking::count(),
            'pending_payments' => \App\Models\Payment::where('status', \App\Enums\PaymentStatus::Pending)->count(),
            'active_teachers' => \App\Models\TeacherProfile::where('is_active', true)->count(),
            'active_students' => \App\Models\User::role('student')->count(),
            'total_revenue' => \App\Models\Payment::where('status', \App\Enums\PaymentStatus::Succeeded)->sum('amount'),
            'total_courses' => \App\Models\Course::count(),
            'pending_reviews' => \App\Models\Review::where('is_approved', false)->count(),
            'open_support_tickets' => \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentBookings = \App\Models\Booking::with(['student', 'teacher.user', 'subject'])
            ->latest()
            ->limit(10)
            ->get();

        $monthlyStats = $this->reportService->getMonthlyStatistics();

        $todayBookings = \App\Models\Booking::whereDate('start_at', Carbon::today())
            ->with(['student', 'teacher.user', 'subject'])
            ->orderBy('start_at')
            ->get();

        $revenueReport = $this->reportService->getRevenueReport(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'monthlyStats',
            'todayBookings',
            'revenueReport'
        ));
    }
}
