<?php

use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->student = User::factory()->create();
    $this->student->assignRole('student');
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);
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

it('allows users to start conversation', function () {
    $this->actingAs($this->student)
        ->post(route('student.messages.start'), [
            'user_id' => $this->teacher->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('conversations', [
        'user_one_id' => $this->student->id,
        'user_two_id' => $this->teacher->id,
    ]);
});

it('creates conversation from booking', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $conversation = Conversation::getOrCreateBetween($this->student, $this->teacher, $booking);

    expect($conversation->booking_id)->toBe($booking->id);
    expect($conversation->user_one_id)->toBe($this->student->id);
    expect($conversation->user_two_id)->toBe($this->teacher->id);
});

it('allows users to send messages', function () {
    $conversation = Conversation::create([
        'user_one_id' => $this->student->id,
        'user_two_id' => $this->teacher->id,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.messages.store'), [
            'conversation_id' => $conversation->id,
            'body' => 'Hello!',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'sender_id' => $this->student->id,
        'body' => 'Hello!',
        'is_read' => false,
    ]);
});

it('prevents users from accessing other conversations', function () {
    $otherStudent = User::factory()->create();
    $otherStudent->assignRole('student');

    $conversation = Conversation::create([
        'user_one_id' => $this->student->id,
        'user_two_id' => $this->teacher->id,
    ]);

    $this->actingAs($otherStudent)
        ->get(route('student.messages.show', $conversation))
        ->assertForbidden();
});

it('marks messages as read when viewing conversation', function () {
    $conversation = Conversation::create([
        'user_one_id' => $this->student->id,
        'user_two_id' => $this->teacher->id,
    ]);

    $message = Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $this->teacher->id,
        'body' => 'Hello student!',
        'is_read' => false,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.messages.show', $conversation));

    expect($message->fresh()->is_read)->toBeTrue();
});
