<?php

use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

it('allows admin to view all users', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $this->actingAs($this->admin)
        ->get(route('admin.users.index'))
        ->assertSuccessful()
        ->assertSee($student->name)
        ->assertSee($teacher->name);
});

it('allows admin to view user details', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($this->admin)
        ->get(route('admin.users.show', $user))
        ->assertSuccessful()
        ->assertSee($user->name)
        ->assertSee($user->email);
});

it('allows admin to promote student to teacher', function () {
    $student = User::factory()->create();
    $student->assignRole('student');
    StudentProfile::factory()->create(['user_id' => $student->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $student), [
            'role' => 'teacher',
        ])
        ->assertRedirect();

    $student->refresh();
    expect($student->hasRole('teacher'))->toBeTrue();
    expect($student->hasRole('student'))->toBeFalse();
    expect($student->teacherProfile)->not->toBeNull();
    expect($student->studentProfile)->toBeNull();
});

it('allows admin to demote teacher to student', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $teacher), [
            'role' => 'student',
        ])
        ->assertRedirect();

    $teacher->refresh();
    expect($teacher->hasRole('student'))->toBeTrue();
    expect($teacher->hasRole('teacher'))->toBeFalse();
    expect($teacher->studentProfile)->not->toBeNull();
    expect($teacher->teacherProfile)->toBeNull();
});

it('prevents admin from changing admin role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $admin), [
            'role' => 'student',
        ])
        ->assertRedirect();

    $admin->refresh();
    expect($admin->hasRole('admin'))->toBeTrue();
});

it('creates teacher profile when promoting to teacher', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $student), [
            'role' => 'teacher',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('teacher_profiles', [
        'user_id' => $student->id,
        'is_active' => true,
    ]);
});

it('creates student profile when demoting to student', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $teacher), [
            'role' => 'student',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('student_profiles', [
        'user_id' => $teacher->id,
    ]);
});

it('validates role field is required', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $user), [])
        ->assertSessionHasErrors('role');
});

it('validates role field is valid', function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $user), [
            'role' => 'invalid-role',
        ])
        ->assertSessionHasErrors('role');
});

it('prevents non-admin from accessing user management', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get(route('admin.users.index'))
        ->assertForbidden();

    $this->actingAs($student)
        ->get(route('admin.users.show', $student))
        ->assertForbidden();

    $this->actingAs($student)
        ->put(route('admin.users.update-role', $student), [
            'role' => 'teacher',
        ])
        ->assertForbidden();
});

it('does not change role if user already has the requested role', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($this->admin)
        ->put(route('admin.users.update-role', $student), [
            'role' => 'student',
        ])
        ->assertRedirect();

    $student->refresh();
    expect($student->hasRole('student'))->toBeTrue();
});
