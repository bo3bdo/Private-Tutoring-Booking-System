<?php

use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);
    $this->student = User::factory()->create();
    $this->student->assignRole('student');
    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);
    $this->slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);
});

it('allows teacher to upload resource for booking', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $file = UploadedFile::fake()->create('document.pdf', 100);

    $this->actingAs($this->teacher)
        ->post(route('teacher.resources.store'), [
            'resourceable_type' => 'App\Models\Booking',
            'resourceable_id' => $booking->id,
            'title' => 'Lesson Notes',
            'description' => 'Important notes',
            'file' => $file,
            'is_public' => true,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('resources', [
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Booking',
        'resourceable_id' => $booking->id,
        'title' => 'Lesson Notes',
        'is_public' => true,
    ]);

    Storage::disk('public')->assertExists('resources/'.$file->hashName());
});

it('allows teacher to upload resource for course', function () {
    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
    ]);

    $file = UploadedFile::fake()->create('material.pdf', 200);

    $this->actingAs($this->teacher)
        ->post(route('teacher.resources.store'), [
            'resourceable_type' => 'App\Models\Course',
            'resourceable_id' => $course->id,
            'title' => 'Course Material',
            'file' => $file,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('resources', [
        'resourceable_type' => 'App\Models\Course',
        'resourceable_id' => $course->id,
    ]);
});

it('allows students to view public resources', function () {
    $resource = Resource::create([
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Course',
        'resourceable_id' => Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
        ])->id,
        'title' => 'Test Resource',
        'file_path' => 'test.pdf',
        'file_name' => 'test.pdf',
        'is_public' => true,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.resources.index'))
        ->assertSuccessful()
        ->assertSee($resource->title);
});

it('prevents students from viewing private resources', function () {
    $resource = Resource::create([
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Course',
        'resourceable_id' => Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
        ])->id,
        'title' => 'Private Resource',
        'file_path' => 'private.pdf',
        'file_name' => 'private.pdf',
        'is_public' => false,
    ]);

    $response = $this->actingAs($this->student)
        ->get(route('student.resources.index'));

    $response->assertSuccessful();
    expect($response->viewData('resources')->contains($resource))->toBeFalse();
});

it('allows teacher to delete their own resources', function () {
    $resource = Resource::create([
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Course',
        'resourceable_id' => Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'subject_id' => $this->subject->id,
        ])->id,
        'title' => 'Test Resource',
        'file_path' => 'test.pdf',
        'file_name' => 'test.pdf',
    ]);

    $this->actingAs($this->teacher)
        ->delete(route('teacher.resources.destroy', $resource))
        ->assertRedirect();

    $this->assertDatabaseMissing('resources', [
        'id' => $resource->id,
    ]);
});

it('allows student to download resource from their booking', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $resource = Resource::create([
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Booking',
        'resourceable_id' => $booking->id,
        'title' => 'Lesson Notes',
        'file_path' => 'notes.pdf',
        'file_name' => 'notes.pdf',
        'is_public' => false, // Private resource
    ]);

    Storage::disk('public')->put($resource->file_path, 'test content');

    $this->actingAs($this->student)
        ->get(route('student.resources.download', $resource))
        ->assertSuccessful();
});

it('prevents student from downloading resource from other students booking', function () {
    $otherStudent = User::factory()->create();
    $otherStudent->assignRole('student');

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id, // This student's booking
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $resource = Resource::create([
        'user_id' => $this->teacher->id,
        'resourceable_type' => 'App\Models\Booking',
        'resourceable_id' => $booking->id,
        'title' => 'Lesson Notes',
        'file_path' => 'notes.pdf',
        'file_name' => 'notes.pdf',
        'is_public' => false,
    ]);

    Storage::disk('public')->put($resource->file_path, 'test content');

    $this->actingAs($otherStudent) // Other student trying to access
        ->get(route('student.resources.download', $resource))
        ->assertForbidden();
});
