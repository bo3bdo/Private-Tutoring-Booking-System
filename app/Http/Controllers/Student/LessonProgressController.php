<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateProgressRequest;
use App\Models\CourseLesson;
use App\Services\CourseProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LessonProgressController extends Controller
{
    public function __construct(
        protected CourseProgressService $progressService
    ) {}

    public function update(UpdateProgressRequest $request, CourseLesson $lesson): JsonResponse|RedirectResponse
    {
        $student = auth()->user();

        // Check if student can access this lesson
        if (! $lesson->canAccess($student)) {
            abort(403, 'You must be enrolled to access this lesson.');
        }

        if ($request->has('watched_seconds')) {
            $this->progressService->updateWatchedSeconds(
                $lesson,
                $student,
                $request->watched_seconds
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Progress updated.');
    }

    public function complete(CourseLesson $lesson): RedirectResponse
    {
        $student = auth()->user();

        // Check if student can access this lesson
        if (! $lesson->canAccess($student)) {
            abort(403, 'You must be enrolled to access this lesson.');
        }

        $this->progressService->markCompleted($lesson, $student);

        return back()->with('success', 'Lesson marked as completed.');
    }
}
