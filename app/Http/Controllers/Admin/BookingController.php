<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['student', 'teacher.user', 'subject', 'timeSlot', 'payment']);

        if ($request->has('filter')) {
            match ($request->filter) {
                'upcoming' => $query->where('start_at', '>', now()),
                'past' => $query->where('start_at', '<', now()),
                'pending' => $query->whereIn('status', ['awaiting_payment', 'awaiting_confirmation']),
                default => null,
            };
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest('start_at')->paginate(20);

        $stats = [
            'total' => Booking::count(),
            'upcoming' => Booking::where('start_at', '>', now())->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'student',
            'teacher.user',
            'subject',
            'timeSlot',
            'location',
            'payment',
            'histories.actor',
            'reviews.user',
            'resources',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }
}
