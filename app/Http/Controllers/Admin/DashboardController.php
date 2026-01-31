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
        $stats = $this->reportService->getDashboardStats();

        $recentBookings = \App\Models\Booking::with(['student', 'teacher.user', 'subject'])
            ->latest('created_at')
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

        // Teacher Performance Analytics
        $teacherPerformance = $this->reportService->getTeacherPerformanceReport(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        // Student Progress Analytics
        $studentProgress = $this->reportService->getStudentProgressReport(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        // Booking Status Distribution
        $bookingsByStatus = \App\Models\Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status->value => $item->count];
            });

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'monthlyStats',
            'todayBookings',
            'revenueReport',
            'teacherPerformance',
            'studentProgress',
            'bookingsByStatus'
        ));
    }
}
