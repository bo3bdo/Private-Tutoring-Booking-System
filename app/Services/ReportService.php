<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\TeacherProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getRevenueReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        $payments = Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'daily_data' => $payments,
            'total_revenue' => $totalRevenue,
            'total_bookings' => $totalBookings,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public function getTeacherPerformanceReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        $teachers = TeacherProfile::with(['user', 'bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])
            ->get()
            ->map(function ($teacher) use ($startDate, $endDate) {
                $bookings = $teacher->bookings()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                $completedBookings = $bookings->where('status', 'completed')->count();
                $totalBookings = $bookings->count();
                $cancelledBookings = $bookings->where('status', 'cancelled')->count();

                $revenue = Payment::whereHas('booking', function ($query) use ($teacher, $startDate, $endDate) {
                    $query->where('teacher_id', $teacher->id)
                        ->whereBetween('created_at', [$startDate, $endDate]);
                })
                    ->where('status', 'succeeded')
                    ->sum('amount');

                $averageRating = $teacher->reviews()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->avg('rating') ?? 0;

                return [
                    'id' => $teacher->id,
                    'name' => $teacher->user->name,
                    'total_bookings' => $totalBookings,
                    'completed_bookings' => $completedBookings,
                    'cancelled_bookings' => $cancelledBookings,
                    'completion_rate' => $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 2) : 0,
                    'revenue' => $revenue,
                    'average_rating' => round($averageRating, 2),
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        return [
            'teachers' => $teachers,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public function getStudentProgressReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        $students = User::role('student')
            ->with(['bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($student) use ($startDate, $endDate) {
                $bookings = $student->bookings()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                $completedBookings = $bookings->where('status', 'completed')->count();
                $totalBookings = $bookings->count();

                $coursesEnrolled = $student->courseEnrollments()
                    ->whereBetween('enrolled_at', [$startDate, $endDate])
                    ->count();

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'total_bookings' => $totalBookings,
                    'completed_bookings' => $completedBookings,
                    'courses_enrolled' => $coursesEnrolled,
                ];
            })
            ->sortByDesc('total_bookings')
            ->values();

        return [
            'students' => $students,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public function getMonthlyStatistics(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthStats = [
            'bookings' => Booking::where('created_at', '>=', $currentMonth)->count(),
            'revenue' => Payment::where('status', 'succeeded')
                ->where('created_at', '>=', $currentMonth)
                ->sum('amount'),
            'students' => User::role('student')
                ->where('created_at', '>=', $currentMonth)
                ->count(),
            'teachers' => TeacherProfile::where('created_at', '>=', $currentMonth)->count(),
        ];

        $lastMonthStats = [
            'bookings' => Booking::whereBetween('created_at', [$lastMonth, $currentMonth->copy()->subSecond()])->count(),
            'revenue' => Payment::where('status', 'succeeded')
                ->whereBetween('created_at', [$lastMonth, $currentMonth->copy()->subSecond()])
                ->sum('amount'),
            'students' => User::role('student')
                ->whereBetween('created_at', [$lastMonth, $currentMonth->copy()->subSecond()])
                ->count(),
            'teachers' => TeacherProfile::whereBetween('created_at', [$lastMonth, $currentMonth->copy()->subSecond()])->count(),
        ];

        return [
            'current_month' => $currentMonthStats,
            'last_month' => $lastMonthStats,
            'growth' => [
                'bookings' => $this->calculateGrowth($currentMonthStats['bookings'], $lastMonthStats['bookings']),
                'revenue' => $this->calculateGrowth($currentMonthStats['revenue'], $lastMonthStats['revenue']),
                'students' => $this->calculateGrowth($currentMonthStats['students'], $lastMonthStats['students']),
                'teachers' => $this->calculateGrowth($currentMonthStats['teachers'], $lastMonthStats['teachers']),
            ],
        ];
    }

    protected function calculateGrowth(float|int $current, float|int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
