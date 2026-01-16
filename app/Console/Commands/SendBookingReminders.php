<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Send booking reminders (24h and 1h before)';

    public function handle(NotificationService $notificationService): int
    {
        $now = Carbon::now();

        $reminder24h = $now->copy()->addHours(24);
        $reminder1h = $now->copy()->addHour();

        $bookings24h = Booking::where('status', BookingStatus::Confirmed)
            ->whereBetween('start_at', [
                $reminder24h->copy()->subMinutes(5),
                $reminder24h->copy()->addMinutes(5),
            ])
            ->get();

        $bookings1h = Booking::where('status', BookingStatus::Confirmed)
            ->whereBetween('start_at', [
                $reminder1h->copy()->subMinutes(5),
                $reminder1h->copy()->addMinutes(5),
            ])
            ->get();

        foreach ($bookings24h as $booking) {
            $notificationService->sendReminder($booking, 24);
            $this->info("Sent 24h reminder for booking #{$booking->id}");
        }

        foreach ($bookings1h as $booking) {
            $notificationService->sendReminder($booking, 1);
            $this->info("Sent 1h reminder for booking #{$booking->id}");
        }

        $this->info("Reminders sent: 24h ({$bookings24h->count()}), 1h ({$bookings1h->count()})");

        return Command::SUCCESS;
    }
}
