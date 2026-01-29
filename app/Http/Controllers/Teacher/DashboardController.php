<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacherProfile;

        // Get bookings with payments
        $bookings = $teacher->bookings()->with(['payment', 'student', 'subject'])->latest('created_at');

        // Calculate earnings statistics
        $todayEarnings = $this->calculateEarnings($bookings->get(), 'today');
        $weekEarnings = $this->calculateEarnings($bookings->get(), 'week');
        $monthEarnings = $this->calculateEarnings($bookings->get(), 'month');
        $totalEarnings = $this->calculateEarnings($bookings->get(), 'all');

        // Count bookings
        $allBookings = $teacher->bookings()->latest('created_at');
        $totalBookings = $allBookings->count();
        $completedBookings = $allBookings->where('status', 'completed')->count();
        $cancelledBookings = $allBookings->where('status', 'cancelled')->count();
        $noShowBookings = $allBookings->where('status', 'no_show')->count();
        $upcomingBookingsCount = $allBookings
            ->where('start_at', '>', now())
            ->where('status', 'confirmed')
            ->count();

        // Performance statistics
        $attendanceRate = $completedBookings > 0
            ? round(($completedBookings / ($completedBookings + $noShowBookings)) * 100, 1)
            : 0;

        // Unique students count
        $uniqueStudents = $allBookings->distinct('student_id')->count('student_id');

        // Average booking value
        $succeededPayments = $bookings->get()->filter(function ($booking) {
            return $booking->payment && $booking->payment->status === PaymentStatus::Succeeded;
        });
        $averageBookingValue = $succeededPayments->count() > 0
            ? $succeededPayments->sum(fn ($b) => $b->payment->amount ?? 0) / $succeededPayments->count()
            : 0;

        // Pending payments
        $pendingPaymentsAmount = $bookings->get()->filter(function ($booking) {
            return $booking->payment
                && in_array($booking->payment->status, [PaymentStatus::Pending, PaymentStatus::Initiated])
                && $booking->status !== \App\Enums\BookingStatus::Cancelled;
        })->sum(fn ($b) => $b->payment->amount ?? 0);

        // Total teaching hours
        $totalHours = $allBookings->where('status', 'completed')
            ->get()
            ->sum(fn ($b) => $b->start_at->diffInMinutes($b->end_at) / 60);

        // Subjects statistics
        $subjectsCount = $teacher->subjects()->count();
        $mostBookedSubject = $allBookings->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

        $mostBookedSubjectName = $mostBookedSubject
            ? \App\Models\Subject::find($mostBookedSubject)?->name ?? 'N/A'
            : 'N/A';

        // Monthly growth (compare with last month)
        $lastMonthEarnings = $this->calculateEarnings($bookings->get(), 'last_month');
        $monthGrowth = $lastMonthEarnings > 0
            ? round((($monthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100, 1)
            : ($monthEarnings > 0 ? 100 : 0);

        // New bookings this month
        $newBookingsThisMonth = $allBookings
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get upcoming bookings for display
        $upcomingBookings = $teacher->bookings()
            ->where('start_at', '>', now())
            ->where('status', 'confirmed')
            ->with(['student', 'subject'])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        $recentBookings = $teacher->bookings()
            ->with(['student', 'subject'])
            ->latest('start_at')
            ->limit(10)
            ->get();

        // Check if profile is incomplete
        $isProfileIncomplete = $teacher->hourly_rate == 0 || $teacher->subjects()->count() == 0;

        return view('teacher.dashboard', compact(
            'todayEarnings',
            'weekEarnings',
            'monthEarnings',
            'totalEarnings',
            'totalBookings',
            'completedBookings',
            'cancelledBookings',
            'noShowBookings',
            'upcomingBookingsCount',
            'attendanceRate',
            'uniqueStudents',
            'averageBookingValue',
            'pendingPaymentsAmount',
            'totalHours',
            'subjectsCount',
            'mostBookedSubjectName',
            'monthGrowth',
            'newBookingsThisMonth',
            'upcomingBookings',
            'recentBookings',
            'isProfileIncomplete',
            'teacher'
        ));
    }

    private function calculateEarnings($bookings, string $period): float
    {
        $filteredBookings = $bookings->filter(function ($booking) use ($period) {
            if (! $booking->payment || $booking->payment->status !== PaymentStatus::Succeeded) {
                return false;
            }

            $paidAt = $booking->payment->paid_at ?? $booking->created_at;

            return match ($period) {
                'today' => $paidAt->isToday(),
                'week' => $paidAt->isCurrentWeek(),
                'month' => $paidAt->isCurrentMonth(),
                'last_month' => $paidAt->isLastMonth(),
                'all' => true,
                default => false,
            };
        });

        return $filteredBookings->sum(function ($booking) {
            return $booking->payment->amount ?? 0;
        });
    }
}
