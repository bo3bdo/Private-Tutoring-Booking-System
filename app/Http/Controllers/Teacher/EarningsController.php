<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacherProfile;

        // Get all bookings with payments for this teacher
        $bookingsQuery = $teacher->bookings()
            ->with(['payment', 'student', 'subject'])
            ->whereHas('payment');

        // Filter by period
        $period = $request->get('period', 'all');
        if ($period !== 'all') {
            $bookingsQuery->whereHas('payment', function ($query) use ($period) {
                $paidAtColumn = 'paid_at';
                $query->where('status', PaymentStatus::Succeeded);

                match ($period) {
                    'today' => $query->whereDate($paidAtColumn, today()),
                    'week' => $query->whereBetween($paidAtColumn, [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereMonth($paidAtColumn, now()->month)
                        ->whereYear($paidAtColumn, now()->year),
                    'year' => $query->whereYear($paidAtColumn, now()->year),
                    default => null,
                };
            });
        }

        // Filter by status
        $status = $request->get('status');
        if ($status) {
            $bookingsQuery->whereHas('payment', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Get bookings for statistics
        $allBookings = $bookingsQuery->get();
        $bookings = $bookingsQuery->latest('created_at')->paginate(20);

        // Calculate statistics
        $todayEarnings = $this->calculateEarnings($allBookings, 'today');
        $weekEarnings = $this->calculateEarnings($allBookings, 'week');
        $monthEarnings = $this->calculateEarnings($allBookings, 'month');
        $yearEarnings = $this->calculateEarnings($allBookings, 'year');
        $totalEarnings = $this->calculateEarnings($allBookings, 'all');

        // Count payments
        $succeededPayments = $allBookings->filter(function ($booking) {
            return $booking->payment && $booking->payment->status === PaymentStatus::Succeeded;
        });
        $totalPayments = $succeededPayments->count();
        $averagePayment = $totalPayments > 0
            ? $succeededPayments->sum(fn ($b) => $b->payment->amount ?? 0) / $totalPayments
            : 0;

        // Count by status
        $pendingCount = $allBookings->filter(function ($booking) {
            return $booking->payment && in_array($booking->payment->status, [PaymentStatus::Pending, PaymentStatus::Initiated]);
        })->count();

        $succeededCount = $succeededPayments->count();

        $failedCount = $allBookings->filter(function ($booking) {
            return $booking->payment && $booking->payment->status === PaymentStatus::Failed;
        })->count();

        return view('teacher.earnings.index', compact(
            'bookings',
            'todayEarnings',
            'weekEarnings',
            'monthEarnings',
            'yearEarnings',
            'totalEarnings',
            'totalPayments',
            'averagePayment',
            'pendingCount',
            'succeededCount',
            'failedCount',
            'period',
            'status'
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
                'year' => $paidAt->isCurrentYear(),
                'all' => true,
                default => false,
            };
        });

        return $filteredBookings->sum(function ($booking) {
            return $booking->payment->amount ?? 0;
        });
    }
}
