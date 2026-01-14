<?php

namespace App\Services;

use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseProgressService
{
    public function updateWatchedSeconds(CourseLesson $lesson, User $student, int $seconds): void
    {
        DB::transaction(function () use ($lesson, $student, $seconds) {
            $progress = LessonProgress::firstOrCreate(
                [
                    'course_id' => $lesson->course_id,
                    'lesson_id' => $lesson->id,
                    'student_id' => $student->id,
                ],
                [
                    'watched_seconds' => 0,
                ]
            );

            // Update watched seconds (only increase)
            if ($seconds > $progress->watched_seconds) {
                $progress->update(['watched_seconds' => $seconds]);
            }

            // Auto-complete if >= 90% of duration
            if ($lesson->duration_seconds && $progress->watched_seconds >= ($lesson->duration_seconds * 0.9)) {
                if (! $progress->completed_at) {
                    $progress->update(['completed_at' => now()]);
                }
            }
        });
    }

    public function markCompleted(CourseLesson $lesson, User $student): void
    {
        DB::transaction(function () use ($lesson, $student) {
            $progress = LessonProgress::firstOrCreate(
                [
                    'course_id' => $lesson->course_id,
                    'lesson_id' => $lesson->id,
                    'student_id' => $student->id,
                ],
                [
                    'watched_seconds' => 0,
                ]
            );

            if (! $progress->completed_at) {
                $progress->update(['completed_at' => now()]);
            }
        });
    }

    public function getProgress(CourseLesson $lesson, User $student): ?LessonProgress
    {
        return LessonProgress::where('lesson_id', $lesson->id)
            ->where('student_id', $student->id)
            ->first();
    }
}
