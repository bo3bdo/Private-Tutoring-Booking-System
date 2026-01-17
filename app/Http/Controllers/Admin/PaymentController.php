<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['student', 'booking.teacher.user', 'booking.subject']);

        if ($request->has('filter')) {
            match ($request->filter) {
                'pending' => $query->where('status', \App\Enums\PaymentStatus::Pending),
                'succeeded' => $query->where('status', \App\Enums\PaymentStatus::Succeeded),
                'failed' => $query->where('status', \App\Enums\PaymentStatus::Failed),
                default => null,
            };
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20);

        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', \App\Enums\PaymentStatus::Pending)->count(),
            'completed' => Payment::where('status', \App\Enums\PaymentStatus::Succeeded)->count(),
            'total_amount' => Payment::where('status', \App\Enums\PaymentStatus::Succeeded)->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'student',
            'booking.teacher.user',
            'booking.subject',
            'booking.timeSlot',
        ]);

        return view('admin.payments.show', compact('payment'));
    }
}
