<?php

use App\Models\Course;
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

    $this->otherTeacher = User::factory()->create();
    $this->otherTeacher->assignRole('teacher');
    $this->otherTeacherProfile = TeacherProfile::factory()->create(['user_id' => $this->otherTeacher->id]);

    $this->subject = Subject::factory()->create(['is_active' => true]);
    $this->otherSubject = Subject::factory()->create(['is_active' => true]);

    // Teacher teaches this subject
    $this->teacherProfile->subjects()->attach($this->subject->id);

    // Other teacher teaches other subject
    $this->otherTeacherProfile->subjects()->attach($this->otherSubject->id);

    $this->courseService = app(CourseService::class);
});

it('allows teachers to create courses for subjects they teach', function () {
    $response = $this->actingAs($this->teacher)
        ->post(route('teacher.courses.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'New Course',
            'description' => 'Course description',
            'price' => 25.00,
            'is_published' => false,
        ]);

    $response->assertRedirect(route('teacher.courses.index'));
    $this->assertDatabaseHas('courses', [
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'New Course',
    ]);
});

it('prevents teachers from creating courses for subjects they do not teach', function () {
    $response = $this->actingAs($this->teacher)
        ->post(route('teacher.courses.store'), [
            'subject_id' => $this->otherSubject->id,
            'title' => 'Unauthorized Course',
            'description' => 'Course description',
            'price' => 25.00,
            'is_published' => false,
        ]);

    $response->assertSessionHasErrors(['subject_id']);
});

it('allows teachers to view their own courses', function () {
    $course = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'My Course',
        'slug' => $this->courseService->generateSlug('My Course'),
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => false,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.courses.show', $course))
        ->assertSuccessful()
        ->assertSee($course->title);
});

it('prevents teachers from viewing other teachers courses', function () {
    $otherCourse = Course::create([
        'teacher_id' => $this->otherTeacher->id,
        'subject_id' => $this->otherSubject->id,
        'title' => 'Other Course',
        'slug' => $this->courseService->generateSlug('Other Course'),
        'price' => 30.00,
        'currency' => 'BHD',
        'is_published' => false,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.courses.show', $otherCourse))
        ->assertForbidden();
});

it('allows teachers to update their own courses', function () {
    $course = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'My Course',
        'slug' => $this->courseService->generateSlug('My Course'),
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => false,
    ]);

    $response = $this->actingAs($this->teacher)
        ->put(route('teacher.courses.update', $course), [
            'subject_id' => $this->subject->id,
            'title' => 'Updated Course',
            'description' => 'Updated description',
            'price' => 30.00,
            'is_published' => false,
        ]);

    $response->assertRedirect(route('teacher.courses.index'));
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'Updated Course',
        'price' => 30.00,
    ]);
});

it('allows teachers to publish their courses', function () {
    $course = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'My Course',
        'slug' => $this->courseService->generateSlug('My Course'),
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => false,
    ]);

    $response = $this->actingAs($this->teacher)
        ->post(route('teacher.courses.publish', $course));

    $response->assertRedirect();
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'is_published' => true,
    ]);
    expect($course->fresh()->published_at)->not->toBeNull();
});
