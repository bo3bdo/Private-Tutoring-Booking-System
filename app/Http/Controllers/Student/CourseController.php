<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function show(Course $course): View
    {
        // Only show published courses to students
        if (! $course->is_published && ! auth()->user()->isAdmin()) {
            abort(404);
        }

        $course->load(['teacher', 'subject', 'lessons' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $isEnrolled = $course->isEnrolledBy(auth()->user());

        return view('student.courses.show', compact('course', 'isEnrolled'));
    }
}
