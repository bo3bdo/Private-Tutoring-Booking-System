<?php

use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Review;
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

it('allows student to create review for booking', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.reviews.store'), [
            'reviewable_type' => 'App\Models\Booking',
            'reviewable_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Great teacher!',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $this->student->id,
        'reviewable_type' => 'App\Models\Booking',
        'reviewable_id' => $booking->id,
        'rating' => 5,
        'is_approved' => true, // Auto-approve reviews
    ]);
});

it('allows student to create review for course', function () {
    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.reviews.store'), [
            'reviewable_type' => 'App\Models\Course',
            'reviewable_id' => $course->id,
            'rating' => 4,
            'comment' => 'Good course!',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $this->student->id,
        'reviewable_type' => 'App\Models\Course',
        'reviewable_id' => $course->id,
        'rating' => 4,
    ]);
});

it('prevents duplicate reviews from same user', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    Review::create([
        'user_id' => $this->student->id,
        'reviewable_type' => 'App\Models\Booking',
        'reviewable_id' => $booking->id,
        'rating' => 5,
    ]);

    $response = $this->actingAs($this->student)
        ->post(route('student.reviews.store'), [
            'reviewable_type' => 'App\Models\Booking',
            'reviewable_id' => $booking->id,
            'rating' => 4,
        ]);

    $response->assertRedirect();
    // Check that notification was set (notify stores data in session)
    expect(session()->has('notify'))->toBeTrue();
});

it('validates rating is between 1 and 5', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.reviews.store'), [
            'reviewable_type' => 'App\Models\Booking',
            'reviewable_id' => $booking->id,
            'rating' => 6,
        ])
        ->assertSessionHasErrors(['rating']);

    $this->actingAs($this->student)
        ->post(route('student.reviews.store'), [
            'reviewable_type' => 'App\Models\Booking',
            'reviewable_id' => $booking->id,
            'rating' => 0,
        ])
        ->assertSessionHasErrors(['rating']);
});

it('allows admin to approve reviews', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online->value,
    ]);

    $review = Review::create([
        'user_id' => $this->student->id,
        'reviewable_type' => 'App\Models\Booking',
        'reviewable_id' => $booking->id,
        'rating' => 5,
        'is_approved' => false,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.reviews.approve', $review))
        ->assertRedirect();

    expect($review->fresh()->is_approved)->toBeTrue();
    expect($review->fresh()->approved_at)->not->toBeNull();
});
