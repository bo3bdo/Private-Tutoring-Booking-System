<?php

use App\Enums\VideoProvider;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
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

    $this->publishedCourse = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'Published Course',
        'slug' => $courseService->generateSlug('Published Course'),
        'description' => 'A published course',
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->unpublishedCourse = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'Unpublished Course',
        'slug' => $courseService->generateSlug('Unpublished Course'),
        'description' => 'An unpublished course',
        'price' => 30.00,
        'currency' => 'BHD',
        'is_published' => false,
        'published_at' => null,
    ]);

    $this->previewLesson = CourseLesson::create([
        'course_id' => $this->publishedCourse->id,
        'title' => 'Preview Lesson',
        'video_provider' => VideoProvider::Youtube,
        'video_url' => 'https://youtube.com/watch?v=preview',
        'is_free_preview' => true,
        'sort_order' => 1,
    ]);

    $this->paidLesson = CourseLesson::create([
        'course_id' => $this->publishedCourse->id,
        'title' => 'Paid Lesson',
        'video_provider' => VideoProvider::Youtube,
        'video_url' => 'https://youtube.com/watch?v=paid',
        'is_free_preview' => false,
        'sort_order' => 2,
    ]);
});

it('allows students to see only published courses', function () {
    $this->actingAs($this->student)
        ->get(route('student.subjects.courses', $this->subject))
        ->assertSuccessful()
        ->assertSee($this->publishedCourse->title)
        ->assertDontSee($this->unpublishedCourse->title);
});

it('prevents students from accessing unpublished courses', function () {
    $this->actingAs($this->student)
        ->get(route('student.courses.show', $this->unpublishedCourse))
        ->assertNotFound();
});

it('allows students to view course details of published courses', function () {
    $this->actingAs($this->student)
        ->get(route('student.courses.show', $this->publishedCourse))
        ->assertSuccessful()
        ->assertSee($this->publishedCourse->title);
});

it('allows students to view course details with preview lessons', function () {
    $this->actingAs($this->student)
        ->get(route('student.courses.show', $this->publishedCourse))
        ->assertSuccessful()
        ->assertSee($this->previewLesson->title);
});

it('prevents students from accessing learning page without enrollment', function () {
    $this->actingAs($this->student)
        ->get(route('student.my-courses.learn', $this->publishedCourse))
        ->assertForbidden();
});

it('allows enrolled students to access all lessons', function () {
    CourseEnrollment::create([
        'course_id' => $this->publishedCourse->id,
        'student_id' => $this->student->id,
        'enrolled_at' => now(),
    ]);

    $this->actingAs($this->student)
        ->get(route('student.my-courses.learn', $this->publishedCourse))
        ->assertSuccessful()
        ->assertSee($this->previewLesson->title)
        ->assertSee($this->paidLesson->title);
});
