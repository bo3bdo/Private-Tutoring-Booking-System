<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarApiController extends Controller
{
    public function events(Request $request): JsonResponse
    {
        $user = auth()->user();
        $start = $request->get('start', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::now()->endOfMonth()->toDateString());

        $events = [];

        if ($user->isAdmin()) {
            $bookings = Booking::with(['student', 'teacher.user', 'subject'])
                ->whereBetween('start_at', [$start, $end])
                ->get();

            foreach ($bookings as $booking) {
                $events[] = [
                    'id' => 'booking-'.$booking->id,
                    'title' => $booking->subject->name.' - '.$booking->student->name,
                    'start' => $booking->start_at->toIso8601String(),
                    'end' => $booking->end_at->toIso8601String(),
                    'backgroundColor' => $this->getBookingColor($booking->status),
                    'borderColor' => $this->getBookingColor($booking->status),
                    'extendedProps' => [
                        'type' => 'booking',
                        'booking_id' => $booking->id,
                        'status' => $booking->status->value,
                        'student' => $booking->student->name,
                        'teacher' => $booking->teacher->user->name,
                        'subject' => $booking->subject->name,
                        'lesson_mode' => $booking->lesson_mode->value,
                    ],
                ];
            }

            $slots = TimeSlot::with(['teacher.user', 'subject', 'booking'])
                ->whereBetween('start_at', [$start, $end])
                ->get();

            foreach ($slots as $slot) {
                if ($slot->isAvailable()) {
                    $events[] = [
                        'id' => 'slot-'.$slot->id,
                        'title' => ($slot->subject ? $slot->subject->name : 'Available').' - '.$slot->teacher->user->name,
                        'start' => $slot->start_at->toIso8601String(),
                        'end' => $slot->end_at->toIso8601String(),
                        'backgroundColor' => '#10b981',
                        'borderColor' => '#10b981',
                        'extendedProps' => [
                            'type' => 'slot',
                            'slot_id' => $slot->id,
                            'status' => $slot->status->value,
                            'teacher' => $slot->teacher->user->name,
                            'subject' => $slot->subject?->name,
                        ],
                    ];
                }
            }
        } elseif ($user->isTeacher()) {
            $teacher = $user->teacherProfile;

            $bookings = Booking::with(['student', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->whereBetween('start_at', [$start, $end])
                ->get();

            foreach ($bookings as $booking) {
                $events[] = [
                    'id' => 'booking-'.$booking->id,
                    'title' => $booking->subject->name.' - '.$booking->student->name,
                    'start' => $booking->start_at->toIso8601String(),
                    'end' => $booking->end_at->toIso8601String(),
                    'backgroundColor' => $this->getBookingColor($booking->status),
                    'borderColor' => $this->getBookingColor($booking->status),
                    'extendedProps' => [
                        'type' => 'booking',
                        'booking_id' => $booking->id,
                        'status' => $booking->status->value,
                        'student' => $booking->student->name,
                        'subject' => $booking->subject->name,
                        'lesson_mode' => $booking->lesson_mode->value,
                    ],
                ];
            }

            $slots = $teacher->timeSlots()
                ->with(['subject'])
                ->whereBetween('start_at', [$start, $end])
                ->get();

            foreach ($slots as $slot) {
                if ($slot->isAvailable()) {
                    $events[] = [
                        'id' => 'slot-'.$slot->id,
                        'title' => ($slot->subject ? $slot->subject->name : 'Available'),
                        'start' => $slot->start_at->toIso8601String(),
                        'end' => $slot->end_at->toIso8601String(),
                        'backgroundColor' => '#10b981',
                        'borderColor' => '#10b981',
                        'extendedProps' => [
                            'type' => 'slot',
                            'slot_id' => $slot->id,
                            'status' => $slot->status->value,
                            'subject' => $slot->subject?->name,
                        ],
                    ];
                }
            }
        } elseif ($user->isStudent()) {
            $bookings = Booking::where('student_id', $user->id)
                ->with(['teacher.user', 'subject'])
                ->whereBetween('start_at', [$start, $end])
                ->get();

            foreach ($bookings as $booking) {
                $events[] = [
                    'id' => 'booking-'.$booking->id,
                    'title' => $booking->subject->name.' - '.$booking->teacher->user->name,
                    'start' => $booking->start_at->toIso8601String(),
                    'end' => $booking->end_at->toIso8601String(),
                    'backgroundColor' => $this->getBookingColor($booking->status),
                    'borderColor' => $this->getBookingColor($booking->status),
                    'extendedProps' => [
                        'type' => 'booking',
                        'booking_id' => $booking->id,
                        'status' => $booking->status->value,
                        'teacher' => $booking->teacher->user->name,
                        'subject' => $booking->subject->name,
                        'lesson_mode' => $booking->lesson_mode->value,
                    ],
                ];
            }
        }

        return response()->json($events);
    }

    protected function getBookingColor($status): string
    {
        return match ($status->value) {
            'awaiting_payment' => '#f59e0b',
            'confirmed' => '#3b82f6',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
            'no_show' => '#6b7280',
            'rescheduled' => '#8b5cf6',
            default => '#6b7280',
        };
    }
}
