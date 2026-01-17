<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = User::role('student')
            ->with(['studentProfile', 'bookings', 'courseEnrollments'])
            ->latest()
            ->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student): View
    {
        $student->load([
            'studentProfile',
            'bookings' => function ($query) {
                $query->with(['teacher.user', 'subject', 'timeSlot', 'payment'])
                    ->latest('start_at');
            },
            'courseEnrollments' => function ($query) {
                $query->with(['course.subject', 'course.teacher', 'course.lessons'])
                    ->latest();
            },
            'coursePurchases' => function ($query) {
                $query->with(['course', 'payment'])
                    ->latest();
            },
            'payments' => function ($query) {
                $query->latest();
            },
        ]);

        $stats = [
            'total_bookings' => $student->bookings()->count(),
            'completed_bookings' => $student->bookings()->where('status', 'completed')->count(),
            'total_courses' => $student->courseEnrollments()->count(),
            'total_spent' => $student->payments()->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.students.show', compact('student', 'stats'));
    }
}
