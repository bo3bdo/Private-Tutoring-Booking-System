<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CoursesController extends Controller
{
    public function index(): View
    {
        $courses = Course::with(['teacher', 'subject', 'lessons', 'enrollments'])
            ->latest()
            ->paginate(20);

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        $course->load(['teacher', 'subject', 'lessons', 'enrollments.student', 'purchases.payment']);

        return view('admin.courses.show', compact('course'));
    }

    public function togglePublish(Course $course): \Illuminate\Http\RedirectResponse
    {
        if ($course->is_published) {
            $course->update([
                'is_published' => false,
                'published_at' => null,
            ]);

            notify()->success()
                ->title('تم إلغاء النشر')
                ->message('تم إلغاء نشر الكورس بنجاح')
                ->send();

            return back();
        }

        $course->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        notify()->success()
            ->title('تم النشر')
            ->message('تم نشر الكورس بنجاح')
            ->send();

        return back();
    }
}
