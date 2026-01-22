<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Services\BookingService;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected DiscountService $discountService
    ) {}

    public function index(Request $request): View
    {
        $query = auth()->user()->bookings()->with(['teacher.user', 'subject', 'timeSlot', 'reviews']);

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

        return view('student.bookings.create', compact('slot', 'subject', 'teacher'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $slot = TimeSlot::findOrFail($request->time_slot_id);

        $this->authorize('book', $slot);

        try {
            $booking = $this->bookingService->createBooking(
                student: auth()->user(),
                timeSlot: $slot,
                subjectId: $request->subject_id,
                lessonMode: $request->lesson_mode,
                locationId: null, // Location will be set by teacher/admin for in-person lessons
                meetingUrl: null, // Meeting URL will be set by teacher/admin
                notes: $request->notes
            );
            // dd($booking);

            if ($booking->isAwaitingPayment()) {
                notify()->info()
                    ->title(__('common.Booking created'))
                    ->message(__('common.Please complete the payment process'))
                    ->send();

                return redirect()->route('student.bookings.pay', $booking);
            }

            notify()->success()
                ->title(__('common.Booking confirmed'))
                ->message(__('common.Booking created successfully'))
                ->send();

            return redirect()->route('student.bookings.show', $booking);
        } catch (\Exception $e) {
            notify()->error()
                ->title(__('common.Error'))
                ->message($e->getMessage())
                ->send();

            return back()->withInput();
        }
    }

    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load(['teacher.user', 'teacher.reviews.user', 'subject', 'timeSlot', 'location', 'payment', 'histories.actor', 'reviews', 'resources']);

        return view('student.bookings.show', compact('booking'));
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
                ->title(__('common.Cancelled'))
                ->message(__('common.Booking cancelled successfully'))
                ->send();

            return redirect()->route('student.bookings.index');
        } catch (\Exception $e) {
            notify()->error()
                ->title(__('common.Error'))
                ->message($e->getMessage())
                ->send();

            return back()->withInput();
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

    public function validateDiscount(Request $request, Booking $booking): JsonResponse
    {
        $this->authorize('view', $booking);

        $request->validate([
            'code' => 'required|string',
        ]);

        $amount = $booking->teacher->hourly_rate ?? 25.00;

        $result = $this->discountService->validateDiscount(
            $request->code,
            auth()->user(),
            $amount
        );

        return response()->json($result);
    }
}
