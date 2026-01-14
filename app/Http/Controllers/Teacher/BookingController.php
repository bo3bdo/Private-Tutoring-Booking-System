<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index(Request $request): View
    {
        $teacher = auth()->user()->teacherProfile;

        $query = $teacher->bookings()->with(['student', 'subject', 'timeSlot']);

        if ($request->has('filter')) {
            match ($request->filter) {
                'upcoming' => $query->where('start_at', '>', now()),
                'past' => $query->where('start_at', '<', now()),
                'confirmed' => $query->where('status', 'confirmed'),
                default => null,
            };
        }

        $bookings = $query->latest('start_at')->paginate(15);

        return view('teacher.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['student', 'subject', 'timeSlot', 'location', 'payment', 'histories.actor']);
        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('teacher.bookings.show', compact('booking', 'locations'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('markStatus', $booking);

        $request->validate([
            'status' => ['required', 'in:completed,no_show'],
        ]);

        $status = BookingStatus::from($request->status);

        $this->bookingService->updateStatus($booking, $status, auth()->user());

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث حالة الحجز بنجاح')
            ->send();

        return back();
    }

    public function reschedule(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('reschedule', $booking);

        $request->validate([
            'time_slot_id' => ['required', 'exists:teacher_time_slots,id'],
        ]);

        $newSlot = TimeSlot::findOrFail($request->time_slot_id);

        try {
            $this->bookingService->rescheduleBooking($booking, $newSlot, auth()->user());

            notify()->success()
                ->title('تم إعادة الجدولة')
                ->message('تم إعادة جدولة الحجز بنجاح')
                ->send();

            return back();
        } catch (\Exception $e) {
            notify()->error()
                ->title('خطأ')
                ->message($e->getMessage())
                ->send();

            return back();
        }
    }

    public function updateMeetingUrl(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $request->validate([
            'meeting_url' => ['required', 'url', 'max:500'],
        ]);

        $oldUrl = $booking->meeting_url;

        $booking->update([
            'meeting_url' => $request->meeting_url,
        ]);

        // Log the change in booking history
        \App\Models\BookingHistory::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'meeting_url_updated',
            'old_payload' => ['meeting_url' => $oldUrl],
            'new_payload' => ['meeting_url' => $request->meeting_url],
        ]);

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث رابط الاجتماع بنجاح')
            ->send();

        return back();
    }

    public function updateLocation(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);

        $oldLocationId = $booking->location_id;

        $booking->update([
            'location_id' => $request->location_id,
        ]);

        // Log the change in booking history
        \App\Models\BookingHistory::create([
            'booking_id' => $booking->id,
            'actor_id' => auth()->id(),
            'action' => 'location_updated',
            'old_payload' => ['location_id' => $oldLocationId],
            'new_payload' => ['location_id' => $request->location_id],
        ]);

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث الموقع بنجاح')
            ->send();

        return back();
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->bookingService->cancelBooking($booking, auth()->user(), $request->cancellation_reason);

            notify()->success()
                ->title('تم الإلغاء')
                ->message('تم إلغاء الحجز بنجاح')
                ->send();

            return back();
        } catch (\Exception $e) {
            notify()->error()
                ->title('خطأ')
                ->message($e->getMessage())
                ->send();

            return back()->withInput();
        }
    }
}
