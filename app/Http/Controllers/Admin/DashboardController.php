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
            'pending_payments' => \App\Models\Payment::where('status', \App\Enums\PaymentStatus::Pending)->count(),
            'active_teachers' => \App\Models\TeacherProfile::where('is_active', true)->count(),
            'active_students' => \App\Models\User::role('student')->count(),
            'total_revenue' => \App\Models\Payment::where('status', \App\Enums\PaymentStatus::Succeeded)->sum('amount'),
            'total_courses' => \App\Models\Course::count(),
            'pending_reviews' => \App\Models\Review::where('is_approved', false)->count(),
            'open_support_tickets' => \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentBookings = \App\Models\Booking::with(['student', 'teacher.user', 'subject'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
