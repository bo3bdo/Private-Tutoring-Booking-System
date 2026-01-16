<?php

use App\Models\TeacherAvailability;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);
});

it('allows teacher to set weekly availability', function () {
    $this->actingAs($this->teacher)
        ->post(route('teacher.availability.store'), [
            'weekday' => 1, // Monday
            'start_time' => '09:00',
            'end_time' => '17:00',
        ])
        ->assertRedirect(route('teacher.availability.index'));

    $this->assertDatabaseHas('teacher_availabilities', [
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => 1,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);
});

it('validates that end time is after start time', function () {
    $this->actingAs($this->teacher)
        ->post(route('teacher.availability.store'), [
            'weekday' => 1,
            'start_time' => '17:00',
            'end_time' => '09:00', // Invalid: end before start
        ])
        ->assertSessionHasErrors(['end_time']);
});

it('validates weekday is between 0 and 6', function () {
    $this->actingAs($this->teacher)
        ->post(route('teacher.availability.store'), [
            'weekday' => 7, // Invalid
            'start_time' => '09:00',
            'end_time' => '17:00',
        ])
        ->assertSessionHasErrors(['weekday']);
});

it('allows teacher to view availability list', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => 1,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.availability.index'))
        ->assertSuccessful();
});

it('allows teacher to delete availability', function () {
    $availability = TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => 1,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $this->actingAs($this->teacher)
        ->delete(route('teacher.availability.destroy', $availability))
        ->assertRedirect(route('teacher.availability.index'));

    $this->assertDatabaseMissing('teacher_availabilities', [
        'id' => $availability->id,
    ]);
});

it('prevents teacher from deleting other teachers availability', function () {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');
    $otherTeacherProfile = TeacherProfile::factory()->create(['user_id' => $otherTeacher->id]);

    $availability = TeacherAvailability::create([
        'teacher_id' => $otherTeacherProfile->id,
        'weekday' => 1,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $this->actingAs($this->teacher)
        ->delete(route('teacher.availability.destroy', $availability))
        ->assertForbidden();
});

it('allows teacher to set multiple availabilities for different weekdays', function () {
    $this->actingAs($this->teacher)
        ->post(route('teacher.availability.store'), [
            'weekday' => 1, // Monday
            'start_time' => '09:00',
            'end_time' => '12:00',
        ])
        ->assertRedirect();

    $this->actingAs($this->teacher)
        ->post(route('teacher.availability.store'), [
            'weekday' => 3, // Wednesday
            'start_time' => '14:00',
            'end_time' => '17:00',
        ])
        ->assertRedirect();

    $availabilityCount = TeacherAvailability::where('teacher_id', $this->teacherProfile->id)->count();
    expect($availabilityCount)->toBe(2);
});
