<?php

use App\Enums\VideoProvider;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\CourseProgressService;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->subject = Subject::factory()->create(['is_active' => true]);
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $courseService = app(CourseService::class);

    $this->course = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'Test Course',
        'slug' => $courseService->generateSlug('Test Course'),
        'description' => 'A test course',
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->lesson1 = CourseLesson::create([
        'course_id' => $this->course->id,
        'title' => 'Lesson 1',
        'video_provider' => VideoProvider::Youtube,
        'video_url' => 'https://youtube.com/watch?v=1',
        'sort_order' => 1,
    ]);

    $this->lesson2 = CourseLesson::create([
        'course_id' => $this->course->id,
        'title' => 'Lesson 2',
        'video_provider' => VideoProvider::Youtube,
        'video_url' => 'https://youtube.com/watch?v=2',
        'sort_order' => 2,
    ]);

    $this->lesson3 = CourseLesson::create([
        'course_id' => $this->course->id,
        'title' => 'Lesson 3',
        'video_provider' => VideoProvider::Youtube,
        'video_url' => 'https://youtube.com/watch?v=3',
        'sort_order' => 3,
    ]);

    CourseEnrollment::create([
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'enrolled_at' => now(),
    ]);

    $this->progressService = app(CourseProgressService::class);
});

it('calculates progress correctly when no lessons completed', function () {
    $progress = $this->course->progressPercentFor($this->student);
    expect($progress)->toBe(0.0);
});

it('calculates progress correctly when one lesson completed', function () {
    LessonProgress::create([
        'course_id' => $this->course->id,
        'lesson_id' => $this->lesson1->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    $progress = $this->course->progressPercentFor($this->student);
    expect($progress)->toBe(33.33); // 1/3 * 100
});

it('calculates progress correctly when all lessons completed', function () {
    LessonProgress::create([
        'course_id' => $this->course->id,
        'lesson_id' => $this->lesson1->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    LessonProgress::create([
        'course_id' => $this->course->id,
        'lesson_id' => $this->lesson2->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    LessonProgress::create([
        'course_id' => $this->course->id,
        'lesson_id' => $this->lesson3->id,
        'student_id' => $this->student->id,
        'completed_at' => now(),
    ]);

    $progress = $this->course->progressPercentFor($this->student);
    expect($progress)->toBe(100.0);
});

it('allows students to mark lessons as completed', function () {
    $response = $this->actingAs($this->student)
        ->post(route('student.lessons.complete', $this->lesson1));

    $response->assertRedirect();

    $this->assertDatabaseHas('lesson_progress', [
        'course_id' => $this->course->id,
        'lesson_id' => $this->lesson1->id,
        'student_id' => $this->student->id,
    ]);

    expect(LessonProgress::where('lesson_id', $this->lesson1->id)
        ->where('student_id', $this->student->id)
        ->first()
        ->completed_at)
        ->not->toBeNull();
});

it('updates watched seconds for lesson progress', function () {
    $response = $this->actingAs($this->student)
        ->post(route('student.lessons.progress', $this->lesson1), [
            'watched_seconds' => 300,
        ]);

    $response->assertRedirect();

    $progress = LessonProgress::where('lesson_id', $this->lesson1->id)
        ->where('student_id', $this->student->id)
        ->first();

    expect($progress->watched_seconds)->toBe(300);
});

it('auto-completes lesson when watched duration reaches 90 percent', function () {
    $this->lesson1->update(['duration_seconds' => 1000]); // 1000 seconds total

    $response = $this->actingAs($this->student)
        ->post(route('student.lessons.progress', $this->lesson1), [
            'watched_seconds' => 900, // 90% of 1000
        ]);

    $response->assertRedirect();

    $progress = LessonProgress::where('lesson_id', $this->lesson1->id)
        ->where('student_id', $this->student->id)
        ->first();

    expect($progress->watched_seconds)->toBe(900);
    expect($progress->completed_at)->not->toBeNull();
});
