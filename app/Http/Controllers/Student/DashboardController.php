<?php

namespace App\Http\Controllers\Student;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        // Get all bookings with payments
        $bookings = $student->bookings()->with(['payment', 'teacher.user', 'subject']);

        // Calculate payment statistics
        $allBookingsList = $bookings->get();
        $totalPaid = $this->calculateTotalPaid($allBookingsList);
        $monthPaid = $this->calculateTotalPaid($allBookingsList, 'month');
        $weekPaid = $this->calculateTotalPaid($allBookingsList, 'week');
        $pendingPayments = $this->calculatePendingPayments($allBookingsList);

        // Count bookings
        $allBookings = $student->bookings();
        $allBookingsList = $allBookings->get();
        $totalBookings = $allBookingsList->count();
        $upcomingBookings = $allBookingsList
            ->filter(fn ($b) => $b->start_at > now() && $b->status === BookingStatus::Confirmed)
            ->count();
        $completedBookings = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Completed)->count();
        $cancelledBookings = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Cancelled)->count();

        // Learning statistics
        $totalHours = $allBookingsList
            ->filter(fn ($b) => $b->status === BookingStatus::Completed)
            ->sum(fn ($b) => $b->start_at->diffInMinutes($b->end_at) / 60);

        $subjectsStudied = $allBookingsList->pluck('subject_id')->filter()->unique()->count();
        $uniqueTeachers = $allBookingsList->pluck('teacher_id')->filter()->unique()->count();

        // Most studied subject
        $bookingsWithSubjects = $allBookings->with('subject')->get();
        $subjectGroups = $bookingsWithSubjects
            ->groupBy('subject_id')
            ->map->count()
            ->sortDesc();

        $mostStudiedSubjectId = $subjectGroups->keys()->first();
        $mostStudiedSubjectName = 'N/A';

        if ($mostStudiedSubjectId) {
            $booking = $bookingsWithSubjects->first(function ($b) use ($mostStudiedSubjectId) {
                return $b->subject_id == $mostStudiedSubjectId;
            });
            $mostStudiedSubjectName = $booking?->subject?->name ?? 'N/A';
        }

        // New bookings this month
        $newBookingsThisMonth = $allBookingsList
            ->filter(fn ($b) => $b->created_at->month === now()->month && $b->created_at->year === now()->year)
            ->count();

        // Average booking duration
        $completedBookingsList = $allBookingsList->filter(fn ($b) => $b->status === BookingStatus::Completed);
        $averageDuration = $completedBookingsList->count() > 0
            ? $completedBookingsList->avg(fn ($b) => $b->start_at->diffInMinutes($b->end_at))
            : 0;

        // Get upcoming bookings for display
        $upcomingBookingsList = $student->bookings()
            ->where('start_at', '>', now())
            ->where('status', BookingStatus::Confirmed->value)
            ->with(['teacher.user', 'subject'])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        // Recent bookings
        $recentBookings = $student->bookings()
            ->with(['teacher.user', 'subject'])
            ->latest('start_at')
            ->limit(10)
            ->get();

        return view('student.dashboard', compact(
            'totalPaid',
            'monthPaid',
            'weekPaid',
            'pendingPayments',
            'totalBookings',
            'upcomingBookings',
            'completedBookings',
            'cancelledBookings',
            'totalHours',
            'subjectsStudied',
            'uniqueTeachers',
            'mostStudiedSubjectName',
            'newBookingsThisMonth',
            'averageDuration',
            'upcomingBookingsList',
            'recentBookings'
        ));
    }

    private function calculateTotalPaid($bookings, string $period = 'all'): float
    {
        $filteredBookings = $bookings->filter(function ($booking) use ($period) {
            if (! $booking->payment || $booking->payment->status !== PaymentStatus::Succeeded) {
                return false;
            }

            $paidAt = $booking->payment->paid_at ?? $booking->created_at;

            return match ($period) {
                'week' => $paidAt->isCurrentWeek(),
                'month' => $paidAt->isCurrentMonth(),
                'all' => true,
                default => false,
            };
        });

        return $filteredBookings->sum(fn ($b) => $b->payment->amount ?? 0);
    }

    private function calculatePendingPayments($bookings): float
    {
        $total = 0;

        foreach ($bookings as $booking) {
            // Skip cancelled bookings
            if ($booking->status === BookingStatus::Cancelled) {
                continue;
            }

            // If booking has a payment record with pending/initiated status
            if ($booking->payment && in_array($booking->payment->status, [PaymentStatus::Pending, PaymentStatus::Initiated])) {
                $total += $booking->payment->amount ?? 0;
            }
            // If booking is awaiting payment but has no payment record yet
            elseif ($booking->status === BookingStatus::AwaitingPayment && ! $booking->payment) {
                // Calculate amount from teacher's hourly rate
                $hourlyRate = $booking->teacher->hourly_rate ?? 25.00;
                $duration = $booking->start_at->diffInMinutes($booking->end_at) / 60;
                $total += $hourlyRate * $duration;
            }
        }

        return $total;
    }
}
