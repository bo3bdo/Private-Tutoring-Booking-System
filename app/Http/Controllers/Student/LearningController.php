<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function learn(Course $course): View
    {
        $this->authorize('accessLearning', $course);

        $student = auth()->user();

        $course->load(['lessons' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        // Get first lesson or first incomplete lesson
        $currentLesson = $course->lessons->first();

        // Try to find first incomplete lesson
        foreach ($course->lessons as $lesson) {
            if (! $lesson->isCompletedBy($student)) {
                $currentLesson = $lesson;
                break;
            }
        }

        $progress = $course->progressPercentFor($student);

        return view('student.my-courses.learn', compact('course', 'currentLesson', 'progress'));
    }

    public function showLesson(Course $course, CourseLesson $lesson): View
    {
        $this->authorize('accessLearning', $course);

        // Check if student can access this lesson
        if (! $lesson->canAccess(auth()->user())) {
            abort(403, 'You must be enrolled to access this lesson.');
        }

        $student = auth()->user();
        $course->load(['lessons' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $progress = $course->progressPercentFor($student);
        $lessonProgress = $lesson->getProgressFor($student);

        return view('student.my-courses.learn', [
            'course' => $course,
            'currentLesson' => $lesson,
            'progress' => $progress,
            'lessonProgress' => $lessonProgress,
        ]);
    }
}
