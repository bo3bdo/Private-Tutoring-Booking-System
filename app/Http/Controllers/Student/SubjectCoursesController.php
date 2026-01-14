<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\View\View;

class SubjectCoursesController extends Controller
{
    public function index(Subject $subject): View
    {
        $courses = $subject->courses()
            ->where('is_published', true)
            ->with(['teacher', 'subject'])
            ->latest('published_at')
            ->paginate(12);

        return view('student.subjects.courses', compact('subject', 'courses'));
    }
}
