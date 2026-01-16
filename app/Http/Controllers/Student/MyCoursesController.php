<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MyCoursesController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();

        $enrollments = $student->courseEnrollments()
            ->with(['course.subject', 'course.teacher', 'course.lessons'])
            ->latest('enrolled_at')
            ->paginate(12);

        return view('student.my-courses.index', compact('enrollments'));
    }
}
