<?php

namespace App\Http\Controllers\Student;

use App\Enums\LessonMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
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
        $query = auth()->user()->bookings()->with(['teacher.user', 'subject', 'timeSlot']);

        if ($request->has('filter')) {
            match ($request->filter) {
                'upcoming' => $query->where('start_at', '>', now()),
                'past' => $query->where('start_at', '<', now()),
                'cancelled' => $query->where('status', 'cancelled'),
                default => null,
            };
        }

        $bookings = $query->latest('start_at')->paginate(15);

        return view('student.bookings.index', compact('bookings'));
    }

    public function create(TimeSlot $slot, Request $request): View
    {
        $this->authorize('view', $slot);

        $subject = $request->get('subject_id')
            ? \App\Models\Subject::findOrFail($request->get('subject_id'))
            : $slot->subject;

        $teacher = $slot->teacher;
        $locations = \App\Models\Location::where('is_active', true)->get();

        return view('student.bookings.create', compact('slot', 'subject', 'teacher', 'locations'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $slot = TimeSlot::findOrFail($request->time_slot_id);

        try {
            $booking = $this->bookingService->createBooking(
                student: auth()->user(),
                timeSlot: $slot,
                subjectId: $request->subject_id,
                lessonMode: $request->lesson_mode,
                locationId: $request->lesson_mode === LessonMode::InPerson->value ? $request->location_id : null,
                meetingUrl: null, // Meeting URL will be set by teacher/admin
                notes: $request->notes
            );

            if ($booking->isAwaitingPayment()) {
                return redirect()->route('student.bookings.pay', $booking)
                    ->with('success', 'Booking created. Please complete payment.');
            }

            return redirect()->route('student.bookings.show', $booking)
                ->with('success', 'Booking confirmed!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['teacher.user', 'subject', 'timeSlot', 'location', 'payment', 'histories.actor']);

        return view('student.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        try {
            $this->bookingService->cancelBooking($booking, auth()->user());

            return redirect()->route('student.bookings.index')
                ->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function pay(Booking $booking): View|RedirectResponse
    {
        $this->authorize('view', $booking);

        if (! $booking->isAwaitingPayment()) {
            return redirect()->route('student.bookings.show', $booking);
        }

        return view('student.bookings.pay', compact('booking'));
    }
}
