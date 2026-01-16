<?php

use App\Models\Booking;
use App\Models\Location;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('allows admin to create subject', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.subjects.store'), [
            'name' => 'Mathematics',
            'description' => 'Math subject',
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.subjects.index'));

    $this->assertDatabaseHas('subjects', [
        'name' => 'Mathematics',
        'description' => 'Math subject',
        'is_active' => true,
    ]);
});

it('allows admin to update subject', function () {
    $subject = Subject::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.subjects.update', $subject), [
            'name' => 'Updated Mathematics',
            'description' => 'Updated description',
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.subjects.index'));

    expect($subject->fresh()->name)->toBe('Updated Mathematics');
    expect($subject->fresh()->is_active)->toBeFalse();
});

it('prevents admin from deleting subject with bookings', function () {
    $subject = Subject::factory()->create();
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $slot = \App\Models\TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    Booking::factory()->create([
        'subject_id' => $subject->id,
        'teacher_id' => $teacherProfile->id,
        'time_slot_id' => $slot->id,
        'student_id' => \App\Models\User::factory()->create()->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.subjects.destroy', $subject))
        ->assertRedirect();

    $this->assertDatabaseHas('subjects', [
        'id' => $subject->id,
    ]);
});

it('allows admin to delete subject without bookings', function () {
    $subject = Subject::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.subjects.destroy', $subject))
        ->assertRedirect(route('admin.subjects.index'));

    $this->assertDatabaseMissing('subjects', [
        'id' => $subject->id,
    ]);
});

it('allows admin to create location', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.locations.store'), [
            'name' => 'Main Office',
            'address' => '123 Main St',
            'map_url' => 'https://maps.google.com/...',
            'notes' => 'Main location',
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.locations.index'));

    $this->assertDatabaseHas('locations', [
        'name' => 'Main Office',
        'address' => '123 Main St',
        'is_active' => true,
    ]);
});

it('allows admin to update location', function () {
    $location = Location::factory()->create([
        'name' => 'Test Location',
        'address' => 'Test Address',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.locations.update', $location), [
            'name' => 'Updated Location',
            'address' => 'Updated Address',
            'map_url' => 'https://maps.google.com/updated',
            'notes' => 'Updated notes',
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.locations.index'));

    expect($location->fresh()->name)->toBe('Updated Location');
    expect($location->fresh()->is_active)->toBeFalse();
});

it('prevents admin from deleting location with bookings', function () {
    $location = Location::factory()->create([
        'name' => 'Test Location',
        'address' => 'Test Address',
    ]);
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $subject = \App\Models\Subject::factory()->create();
    $slot = \App\Models\TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    Booking::factory()->create([
        'location_id' => $location->id,
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'student_id' => \App\Models\User::factory()->create()->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::InPerson,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.locations.destroy', $location))
        ->assertRedirect();

    $this->assertDatabaseHas('locations', [
        'id' => $location->id,
    ]);
});

it('allows admin to create teacher', function () {
    $subject = Subject::factory()->create(['is_active' => true]);

    $this->actingAs($this->admin)
        ->post(route('admin.teachers.store'), [
            'name' => 'John Teacher',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'bio' => 'Experienced teacher',
            'hourly_rate' => 30.00,
            'is_active' => true,
            'supports_online' => true,
            'supports_in_person' => true,
            'subjects' => [$subject->id],
        ])
        ->assertRedirect(route('admin.teachers.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'John Teacher',
        'email' => 'john@example.com',
    ]);

    $user = User::where('email', 'john@example.com')->first();
    expect($user->hasRole('teacher'))->toBeTrue();
    expect((float) $user->teacherProfile->hourly_rate)->toBe(30.0);
});

it('allows admin to update teacher', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create([
        'user_id' => $teacher->id,
        'hourly_rate' => 25.00,
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.teachers.update', $teacherProfile), [
            'name' => 'Updated Teacher',
            'email' => 'updated@example.com',
            'bio' => 'Updated bio',
            'hourly_rate' => 35.00,
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.teachers.index'));

    expect($teacher->fresh()->name)->toBe('Updated Teacher');
    expect((float) $teacherProfile->fresh()->hourly_rate)->toBe(35.0);
});

it('prevents admin from deleting teacher with bookings', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $subject = \App\Models\Subject::factory()->create();
    $slot = \App\Models\TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    Booking::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'student_id' => \App\Models\User::factory()->create()->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.teachers.destroy', $teacherProfile))
        ->assertRedirect();

    $this->assertDatabaseHas('teacher_profiles', [
        'id' => $teacherProfile->id,
    ]);
});

it('allows admin to delete teacher without bookings', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.teachers.destroy', $teacherProfile))
        ->assertRedirect(route('admin.teachers.index'));

    $this->assertDatabaseMissing('teacher_profiles', [
        'id' => $teacherProfile->id,
    ]);
    $this->assertDatabaseMissing('users', [
        'id' => $teacher->id,
    ]);
});

it('prevents non-admin from accessing admin CRUD routes', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $subject = Subject::factory()->create();

    $this->actingAs($student)
        ->get(route('admin.subjects.index'))
        ->assertForbidden();

    $this->actingAs($student)
        ->post(route('admin.subjects.store'), [
            'name' => 'Test',
        ])
        ->assertForbidden();

    $this->actingAs($student)
        ->delete(route('admin.subjects.destroy', $subject))
        ->assertForbidden();
});
