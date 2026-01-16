<?php

use App\Enums\SlotStatus;
use App\Models\Subject;
use App\Models\TeacherAvailability;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\SlotGenerationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $this->slotService = app(SlotGenerationService::class);
});

it('generates slots from teacher availability', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->addDay()->dayOfWeek,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $startDate = Carbon::now()->addDay()->startOfDay();
    $endDate = Carbon::now()->addDay()->endOfDay();

    $generated = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    expect($generated)->toBeGreaterThan(0);
    $this->assertDatabaseHas('teacher_time_slots', [
        'teacher_id' => $this->teacherProfile->id,
        'status' => SlotStatus::Available->value,
    ]);
});

it('skips past dates when generating slots', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->subDay()->dayOfWeek,
        'start_time' => '09:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $startDate = Carbon::now()->subDay()->startOfDay();
    $endDate = Carbon::now()->subDay()->endOfDay();

    $generated = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    expect($generated)->toBe(0);
});

it('prevents generating duplicate slots', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->addDay()->dayOfWeek,
        'start_time' => '09:00',
        'end_time' => '10:00',
        'is_active' => true,
    ]);

    $startDate = Carbon::now()->addDay()->setTime(9, 0);
    $endDate = Carbon::now()->addDay()->setTime(10, 0);

    // Generate slots first time
    $generated1 = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    // Generate slots second time (should not create duplicates)
    $generated2 = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    expect($generated1)->toBe(1);
    expect($generated2)->toBe(0); // Should not generate duplicates

    $slotCount = TimeSlot::where('teacher_id', $this->teacherProfile->id)
        ->whereDate('start_at', $startDate->toDateString())
        ->count();

    expect($slotCount)->toBe(1);
});

it('handles multiple availabilities per day', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->addDay()->dayOfWeek,
        'start_time' => '09:00',
        'end_time' => '12:00',
        'is_active' => true,
    ]);

    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->addDay()->dayOfWeek,
        'start_time' => '14:00',
        'end_time' => '17:00',
        'is_active' => true,
    ]);

    $startDate = Carbon::now()->addDay()->startOfDay();
    $endDate = Carbon::now()->addDay()->endOfDay();

    $generated = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    expect($generated)->toBeGreaterThan(3); // Should generate slots for both time ranges
});

it('generates slots with specific subject when provided', function () {
    TeacherAvailability::create([
        'teacher_id' => $this->teacherProfile->id,
        'weekday' => Carbon::now()->addDay()->dayOfWeek,
        'start_time' => '09:00',
        'end_time' => '10:00',
        'is_active' => true,
    ]);

    $startDate = Carbon::now()->addDay()->setTime(9, 0);
    $endDate = Carbon::now()->addDay()->setTime(10, 0);

    $generated = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60,
        subjectId: $this->subject->id
    );

    expect($generated)->toBe(1);

    $slot = TimeSlot::where('teacher_id', $this->teacherProfile->id)->first();
    expect($slot->subject_id)->toBe($this->subject->id);
});

it('returns zero when teacher has no availability', function () {
    $startDate = Carbon::now()->addDay()->startOfDay();
    $endDate = Carbon::now()->addDay()->endOfDay();

    $generated = $this->slotService->generateSlots(
        teacher: $this->teacherProfile,
        startDate: $startDate,
        endDate: $endDate,
        durationMinutes: 60
    );

    expect($generated)->toBe(0);
});

it('allows teacher to block a slot', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->actingAs($this->teacher)
        ->post(route('teacher.slots.block', $slot))
        ->assertRedirect();

    expect($slot->fresh()->status)->toBe(SlotStatus::Blocked);
});

it('allows teacher to unblock a slot', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Blocked,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->actingAs($this->teacher)
        ->post(route('teacher.slots.unblock', $slot))
        ->assertRedirect();

    expect($slot->fresh()->status)->toBe(SlotStatus::Available);
});

it('prevents teacher from blocking other teachers slots', function () {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');
    $otherTeacherProfile = TeacherProfile::factory()->create(['user_id' => $otherTeacher->id]);

    $slot = TimeSlot::factory()->create([
        'teacher_id' => $otherTeacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->actingAs($this->teacher)
        ->post(route('teacher.slots.block', $slot))
        ->assertForbidden();
});
