<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreCourseRequest;
use App\Http\Requests\Teacher\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Course::class);

        $teacher = auth()->user()->teacherProfile;
        $courses = Course::where('teacher_id', auth()->id())
            ->with(['subject', 'lessons', 'enrollments'])
            ->latest()
            ->paginate(15);

        return view('teacher.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);

        $teacher = auth()->user()->teacherProfile;
        $subjects = $teacher->subjects()->where('is_active', true)->get();

        return view('teacher.courses.create', compact('subjects'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $slug = $this->courseService->generateSlug($request->title);

        $data = $request->validated();
        $data['teacher_id'] = auth()->id();
        $data['slug'] = $slug;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course = Course::create($data);

        if ($request->boolean('is_published')) {
            $this->courseService->publish($course);
        }

        notify()->success()
            ->title('تم الإنشاء')
            ->message('تم إنشاء الكورس بنجاح')
            ->send();

        return redirect()->route('teacher.courses.index');
    }

    public function show(Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['subject', 'lessons', 'enrollments.student']);

        return view('teacher.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        $teacher = auth()->user()->teacherProfile;
        $subjects = $teacher->subjects()->where('is_active', true)->get();

        return view('teacher.courses.edit', compact('course', 'subjects'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_path) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        if ($request->title !== $course->title) {
            $data['slug'] = $this->courseService->generateSlug($request->title);
        }

        $course->update($data);

        if ($request->boolean('is_published') && ! $course->is_published) {
            $this->courseService->publish($course);
        } elseif (! $request->boolean('is_published') && $course->is_published) {
            $this->courseService->unpublish($course);
        }

        notify()->success()
            ->title('تم التحديث')
            ->message('تم تحديث الكورس بنجاح')
            ->send();

        return redirect()->route('teacher.courses.index');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        if ($course->enrollments()->exists()) {
            notify()->error()
                ->title('خطأ')
                ->message('لا يمكن حذف الكورس الذي يحتوي على تسجيلات')
                ->send();

            return back();
        }

        if ($course->thumbnail_path) {
            Storage::disk('public')->delete($course->thumbnail_path);
        }

        $course->delete();

        notify()->success()
            ->title('تم الحذف')
            ->message('تم حذف الكورس بنجاح')
            ->send();

        return redirect()->route('teacher.courses.index');
    }

    public function publish(Course $course): RedirectResponse
    {
        $this->authorize('publish', $course);

        $this->courseService->publish($course);

        notify()->success()
            ->title('تم النشر')
            ->message('تم نشر الكورس بنجاح')
            ->send();

        return back();
    }

    public function unpublish(Course $course): RedirectResponse
    {
        $this->authorize('publish', $course);

        $this->courseService->unpublish($course);

        notify()->success()
            ->title('تم إلغاء النشر')
            ->message('تم إلغاء نشر الكورس بنجاح')
            ->send();

        return back();
    }
}
