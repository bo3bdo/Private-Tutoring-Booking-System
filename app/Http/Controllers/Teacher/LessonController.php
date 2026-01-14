<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreLessonRequest;
use App\Http\Requests\Teacher\UpdateLessonRequest;
use App\Models\Course;
use App\Models\CourseLesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('manageLessons', $course);

        $lessons = $course->lessons()->orderBy('sort_order')->get();

        $lessonsData = $lessons->mapWithKeys(function ($lesson) {
            return [$lesson->id => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'summary' => $lesson->summary,
                'sort_order' => $lesson->sort_order,
                'video_provider' => $lesson->video_provider->value,
                'video_url' => $lesson->video_url,
                'duration_seconds' => $lesson->duration_seconds,
                'is_free_preview' => $lesson->is_free_preview,
            ]];
        });

        return view('teacher.courses.lessons', compact('course', 'lessons', 'lessonsData'));
    }

    public function store(StoreLessonRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('manageLessons', $course);

        $data = $request->validated();
        $data['course_id'] = $course->id;

        if (! isset($data['sort_order'])) {
            $maxOrder = $course->lessons()->max('sort_order') ?? 0;
            $data['sort_order'] = $maxOrder + 1;
        }

        CourseLesson::create($data);

        return redirect()->route('teacher.courses.lessons', $course)
            ->with('success', 'Lesson created successfully.');
    }

    public function update(UpdateLessonRequest $request, CourseLesson $lesson): RedirectResponse
    {
        $this->authorize('manageLessons', $lesson->course);

        $lesson->update($request->validated());

        return back()->with('success', 'Lesson updated successfully.');
    }

    public function destroy(CourseLesson $lesson): RedirectResponse
    {
        $this->authorize('manageLessons', $lesson->course);

        $course = $lesson->course;
        $lesson->delete();

        return redirect()->route('teacher.courses.lessons', $course)
            ->with('success', 'Lesson deleted successfully.');
    }
}
