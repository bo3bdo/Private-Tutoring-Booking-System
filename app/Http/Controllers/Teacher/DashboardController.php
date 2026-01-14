<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $teacher = auth()->user()->teacherProfile;
        $upcomingBookings = $teacher->bookings()
            ->where('start_at', '>', now())
            ->where('status', 'confirmed')
            ->with(['student', 'subject'])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        $recentBookings = $teacher->bookings()
            ->with(['student', 'subject'])
            ->latest('start_at')
            ->limit(10)
            ->get();

        return view('teacher.dashboard', compact('upcomingBookings', 'recentBookings'));
    }
}
