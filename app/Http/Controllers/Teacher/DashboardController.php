<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacherProfile;

        $bookingsCollection = $teacher->bookings()
            ->with(['payment', 'student', 'subject'])
            ->latest('created_at')
            ->get();

        $todayEarnings = $this->calculateEarnings($bookingsCollection, 'today');
        $weekEarnings = $this->calculateEarnings($bookingsCollection, 'week');
        $monthEarnings = $this->calculateEarnings($bookingsCollection, 'month');
        $totalEarnings = $this->calculateEarnings($bookingsCollection, 'all');
        $lastMonthEarnings = $this->calculateEarnings($bookingsCollection, 'last_month');

        $totalBookings = $bookingsCollection->count();
        $completedBookings = $bookingsCollection->where('status', BookingStatus::Completed)->count();
        $cancelledBookings = $bookingsCollection->where('status', BookingStatus::Cancelled)->count();
        $noShowBookings = $bookingsCollection->where('status', BookingStatus::NoShow)->count();
        $upcomingBookingsCount = $bookingsCollection
            ->filter(fn ($b) => $b->start_at > now() && $b->status === BookingStatus::Confirmed)
            ->count();

        $attendanceRate = $completedBookings > 0
            ? round(($completedBookings / ($completedBookings + $noShowBookings)) * 100, 1)
            : 0;

        $uniqueStudents = $bookingsCollection->pluck('student_id')->unique()->filter()->count();

        $succeededPayments = $bookingsCollection->filter(fn ($b) => $b->payment && $b->payment->status === PaymentStatus::Succeeded);
        $averageBookingValue = $succeededPayments->count() > 0
            ? $succeededPayments->sum(fn ($b) => $b->payment->amount ?? 0) / $succeededPayments->count()
            : 0;

        $pendingPaymentsAmount = $bookingsCollection->filter(function ($booking) {
            return $booking->payment
                && in_array($booking->payment->status, [PaymentStatus::Pending, PaymentStatus::Initiated])
                && $booking->status !== BookingStatus::Cancelled;
        })->sum(fn ($b) => $b->payment->amount ?? 0);

        $totalHours = $bookingsCollection
            ->where('status', BookingStatus::Completed)
            ->sum(fn ($b) => $b->start_at->diffInMinutes($b->end_at) / 60);

        $subjectsCount = $teacher->subjects()->count();
        $mostBookedSubject = $bookingsCollection->groupBy('subject_id')->map->count()->sortDesc()->keys()->first();
        $mostBookedSubjectName = $mostBookedSubject
            ? \App\Models\Subject::find($mostBookedSubject)?->name ?? 'N/A'
            : 'N/A';

        $monthGrowth = $lastMonthEarnings > 0
            ? round((($monthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100, 1)
            : ($monthEarnings > 0 ? 100 : 0);

        $newBookingsThisMonth = $bookingsCollection
            ->filter(fn ($b) => $b->created_at->month === now()->month && $b->created_at->year === now()->year)
            ->count();

        $upcomingBookings = $teacher->bookings()
            ->where('start_at', '>', now())
            ->where('status', BookingStatus::Confirmed->value)
            ->with(['student', 'subject'])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        $recentBookings = $teacher->bookings()
            ->with(['student', 'subject'])
            ->latest('start_at')
            ->limit(10)
            ->get();

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
