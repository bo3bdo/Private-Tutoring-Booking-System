<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function createBooking(
        User $student,
        TimeSlot $timeSlot,
        int $subjectId,
        string $lessonMode,
        ?int $locationId = null,
        ?string $meetingUrl = null,
        ?string $notes = null
    ): Booking {
        return DB::transaction(function () use (
            $student,
            $timeSlot,
            $subjectId,
            $lessonMode,
            $locationId,
            $meetingUrl,
            $notes
        ) {
            $lockedSlot = TimeSlot::where('id', $timeSlot->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedSlot || $lockedSlot->status !== SlotStatus::Available) {
                throw new \Exception('This slot is no longer available. Please choose another time.');
            }

            // Check if there's an existing booking for this slot (even if cancelled)
            $existingBooking = Booking::where('time_slot_id', $timeSlot->id)
                ->lockForUpdate()
                ->first();

            // If there's a cancelled booking, delete it to allow a new booking
            if ($existingBooking && $existingBooking->status === BookingStatus::Cancelled) {
                $existingBooking->delete();
            } elseif ($existingBooking) {
                // If there's an active booking, throw an error
                throw new \Exception('This slot is already booked. Please choose another time.');
            }

            $paymentRequired = Setting::get('payment_required', true);
            $initialStatus = $paymentRequired
                ? BookingStatus::AwaitingPayment
                : BookingStatus::Confirmed;

            $booking = Booking::create([
                'student_id' => $student->id,
                'teacher_id' => $timeSlot->teacher_id,
                'subject_id' => $subjectId,
                'time_slot_id' => $timeSlot->id,
                'start_at' => $timeSlot->start_at,
                'end_at' => $timeSlot->end_at,
                'status' => $initialStatus,
                'lesson_mode' => $lessonMode,
                'location_id' => $locationId,
                'meeting_provider' => $timeSlot->teacher->default_meeting_provider,
                'meeting_url' => $meetingUrl,
                'notes' => $notes,
            ]);

            $lockedSlot->update(['status' => SlotStatus::Booked]);

            $this->logHistory($booking, 'created', null, $booking->status, [
                'time_slot_id' => $timeSlot->id,
                'lesson_mode' => $lessonMode,
            ]);

            if ($initialStatus === BookingStatus::Confirmed) {
                $this->notificationService->sendBookingCreated($booking);
            } else {
                $this->notificationService->sendBookingAwaitingPayment($booking);
            }

            return $booking;
        });
    }

    public function cancelBooking(Booking $booking, User $actor, ?string $reason = null): void
    {
        DB::transaction(function () use ($booking, $actor, $reason) {
            $oldStatus = $booking->status;

            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            if ($booking->timeSlot) {
                $booking->timeSlot->update(['status' => SlotStatus::Available]);
            }

            $this->logHistory($booking, 'cancelled', $oldStatus, $booking->status, [
                'reason' => $reason,
                'actor_id' => $actor->id,
            ]);

            $this->notificationService->sendBookingCancelled($booking);
        });
    }

    public function rescheduleBooking(
        Booking $booking,
        TimeSlot $newTimeSlot,
        User $actor
    ): void {
        DB::transaction(function () use ($booking, $newTimeSlot, $actor) {
            $oldSlot = $booking->timeSlot;
            $oldStart = $booking->start_at;
            $oldEnd = $booking->end_at;

            $lockedSlot = TimeSlot::where('id', $newTimeSlot->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedSlot || $lockedSlot->status !== SlotStatus::Available) {
                throw new \Exception('The selected slot is no longer available.');
            }

            $booking->update([
                'time_slot_id' => $newTimeSlot->id,
                'start_at' => $newTimeSlot->start_at,
                'end_at' => $newTimeSlot->end_at,
                'status' => BookingStatus::Rescheduled,
            ]);

            if ($oldSlot) {
                $oldSlot->update(['status' => SlotStatus::Available]);
            }

            $lockedSlot->update(['status' => SlotStatus::Booked]);

            $this->logHistory($booking, 'rescheduled', $booking->status, BookingStatus::Rescheduled, [
                'old_slot_id' => $oldSlot?->id,
                'old_start_at' => $oldStart,
                'old_end_at' => $oldEnd,
                'new_slot_id' => $newTimeSlot->id,
                'new_start_at' => $newTimeSlot->start_at,
                'new_end_at' => $newTimeSlot->end_at,
                'actor_id' => $actor->id,
            ]);

            $this->notificationService->sendBookingRescheduled($booking);
        });
    }

    public function updateStatus(
        Booking $booking,
        BookingStatus $newStatus,
        User $actor
    ): void {
        DB::transaction(function () use ($booking, $newStatus, $actor) {
            $oldStatus = $booking->status;

            $updates = ['status' => $newStatus];

            if ($newStatus === BookingStatus::Completed) {
                $updates['completed_at'] = now();
            }

            $booking->update($updates);

            $this->logHistory($booking, 'status_changed', $oldStatus, $newStatus, [
                'actor_id' => $actor->id,
            ]);

            if ($newStatus === BookingStatus::Completed) {
                $this->notificationService->sendBookingCompleted($booking);
            } elseif ($newStatus === BookingStatus::NoShow) {
                $this->notificationService->sendBookingNoShow($booking);
            }
        });
    }

    public function confirmBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $oldStatus = $booking->status;

            $booking->update([
                'status' => BookingStatus::Confirmed,
            ]);

            $this->logHistory($booking, 'confirmed', $oldStatus, BookingStatus::Confirmed);

            $this->notificationService->sendBookingConfirmed($booking);
        });
    }

    protected function logHistory(
        Booking $booking,
        string $action,
        ?BookingStatus $oldStatus = null,
        ?BookingStatus $newStatus = null,
        ?array $payload = null
    ): void {
        BookingHistory::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => $action,
            'old_status' => $oldStatus?->value,
            'new_status' => $newStatus?->value,
            'old_payload' => $payload,
            'new_payload' => $payload,
        ]);
    }
}
