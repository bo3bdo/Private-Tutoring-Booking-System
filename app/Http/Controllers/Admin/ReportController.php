<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index(Request $request): View
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        $revenueReport = $this->reportService->getRevenueReport($startDate, $endDate);
        $teacherReport = $this->reportService->getTeacherPerformanceReport($startDate, $endDate);
        $studentReport = $this->reportService->getStudentProgressReport($startDate, $endDate);
        $monthlyStats = $this->reportService->getMonthlyStatistics();

        return view('admin.reports.index', compact(
            'revenueReport',
            'teacherReport',
            'studentReport',
            'monthlyStats',
            'startDate',
            'endDate'
        ));
    }
}
