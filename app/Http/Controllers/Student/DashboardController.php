<?php

namespace App\Http\Controllers\Student;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TeacherRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        // Get all bookings with payments
        $bookings = $student->bookings()->with(['payment', 'teacher.user', 'subject'])->latest();

        // Calculate payment statistics
        $allBookingsList = $bookings->get();
        $totalPaid = $this->calculateTotalPaid($allBookingsList);
        $monthPaid = $this->calculateTotalPaid($allBookingsList, 'month');
        $weekPaid = $this->calculateTotalPaid($allBookingsList, 'week');
        $pendingPayments = $this->calculatePendingPayments($allBookingsList);

        // Count bookings
        $allBookings = $student->bookings()->latest();
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

        // Check for completed bookings without reviews and show notifications
        $this->checkAndNotifyUnreviewedBookings($student);

        // Check if user has a teacher request
        $teacherRequest = TeacherRequest::where('user_id', $student->id)
            ->latest()
            ->first();

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
            'recentBookings',
            'teacherRequest'
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

    private function checkAndNotifyUnreviewedBookings($student): void
    {
        // Get completed bookings that don't have reviews from this student
        $unreviewedBookings = Booking::where('student_id', $student->id)
            ->where('status', BookingStatus::Completed->value)
            ->whereDoesntHave('reviews', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })
            ->with(['teacher.user', 'subject'])
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        if ($unreviewedBookings->isEmpty()) {
            return;
        }

        // Show notification for each unreviewed booking
        foreach ($unreviewedBookings as $booking) {
            $teacherName = $booking->teacher->user->name ?? __('common.Teacher');
            $subjectName = $booking->subject->name ?? __('common.Subject');

            notify()
                ->info()
                ->title(__('common.Please rate the lesson'))
                ->message(__('common.You have a completed booking with :teacher in :subject - Please rate the lesson', [
                    'teacher' => $teacherName,
                    'subject' => $subjectName,
                ]))
                ->duration(10000) // 10 seconds
                ->actions([
                    \Mckenziearts\Notify\Action\NotifyAction::make()
                        ->label(__('common.View Booking'))
                        ->url(route('student.bookings.show', $booking)),
                ])
                ->send();
        }
    }
}
