<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_bookings' => \App\Models\Booking::count(),
            'pending_payments' => \App\Models\Payment::where('status', 'pending')->count(),
            'active_teachers' => \App\Models\TeacherProfile::where('is_active', true)->count(),
            'active_students' => \App\Models\User::role('student')->count(),
        ];

        $recentBookings = \App\Models\Booking::with(['student', 'teacher.user', 'subject'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
