<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

it('displays admin dashboard with correct statistics', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id, 'is_active' => true]);

    $student = User::factory()->create();
    $student->assignRole('student');

    $subject = Subject::factory()->create();

    // Create multiple slots with unique times for multiple bookings
    $bookings = [];
    for ($i = 0; $i < 5; $i++) {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $teacherProfile->id,
            'subject_id' => $subject->id,
            'start_at' => now()->addDays($i + 1)->addHours(9),
            'end_at' => now()->addDays($i + 1)->addHours(10),
        ]);

        $bookings[] = Booking::factory()->create([
            'teacher_id' => $teacherProfile->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'time_slot_id' => $slot->id,
            'start_at' => $slot->start_at,
            'end_at' => $slot->end_at,
            'lesson_mode' => \App\Enums\LessonMode::Online,
        ]);
    }

    Payment::factory()->create([
        'booking_id' => $bookings[0]->id,
        'student_id' => $student->id,
        'status' => PaymentStatus::Pending,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee(__('common.Admin Dashboard'))
        ->assertSee(__('common.Total Bookings'))
        ->assertSee(__('common.Pending Payments'))
        ->assertSee(__('common.Active Teachers'))
        ->assertSee(__('common.Active Students'));
});

it('displays teacher dashboard with earnings statistics', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $student = User::factory()->create();
    $student->assignRole('student');

    $subject = Subject::factory()->create();
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $student->id,
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Completed,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    Payment::factory()->create([
        'booking_id' => $booking->id,
        'student_id' => $student->id,
        'status' => PaymentStatus::Succeeded,
        'amount' => 25.00,
        'paid_at' => now(),
        'provider' => \App\Enums\PaymentProvider::Stripe,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.dashboard'))
        ->assertSuccessful()
        ->assertSee(__('common.Teacher Dashboard'))
        ->assertSee(__('common.Total Earnings'))
        ->assertSee(__('common.Active Students'));
});

it('displays student dashboard with booking statistics', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $subject = Subject::factory()->create();
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
    ]);

    Booking::factory()->create([
        'student_id' => $student->id,
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $this->actingAs($student)
        ->get(route('student.dashboard'))
        ->assertSuccessful()
        ->assertSeeText(__('common.Student Dashboard'))
        ->assertSeeText(__('common.Total Bookings'))
        ->assertSeeText(__('common.Upcoming Bookings'));
});

it('calculates correct statistics for admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id, 'is_active' => true]);

    $student = User::factory()->create();
    $student->assignRole('student');

    $subject = Subject::factory()->create();

    // Create multiple slots with unique times for multiple bookings
    $bookings = [];
    for ($i = 0; $i < 10; $i++) {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $teacherProfile->id,
            'subject_id' => $subject->id,
            'start_at' => now()->addDays($i + 1)->addHours(9),
            'end_at' => now()->addDays($i + 1)->addHours(10),
        ]);

        $bookings[] = Booking::factory()->create([
            'teacher_id' => $teacherProfile->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'time_slot_id' => $slot->id,
            'start_at' => $slot->start_at,
            'end_at' => $slot->end_at,
            'lesson_mode' => \App\Enums\LessonMode::Online,
        ]);
    }

    for ($i = 0; $i < 3; $i++) {
        Payment::factory()->create([
            'booking_id' => $bookings[$i]->id,
            'student_id' => $student->id,
            'status' => PaymentStatus::Pending,
        ]);
    }

    $response = $this->actingAs($admin)
        ->get(route('admin.dashboard'));

    $response->assertSuccessful();
    // Statistics should be displayed
    expect($response->viewData('stats'))->toHaveKey('total_bookings');
    expect($response->viewData('stats')['total_bookings'])->toBe(10);
});

it('calculates correct earnings for teacher dashboard', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $student = User::factory()->create();
    $student->assignRole('student');

    $subject = Subject::factory()->create();
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $student->id,
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Completed,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    Payment::factory()->create([
        'booking_id' => $booking->id,
        'student_id' => $student->id,
        'status' => PaymentStatus::Succeeded,
        'amount' => 50.00,
        'paid_at' => now(),
        'provider' => \App\Enums\PaymentProvider::Stripe,
    ]);

    $response = $this->actingAs($teacher)
        ->get(route('teacher.dashboard'));

    $response->assertSuccessful();
    expect($response->viewData('totalEarnings'))->toBeGreaterThan(0);
});

it('shows upcoming bookings on student dashboard', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');
    $teacherProfile = TeacherProfile::factory()->create(['user_id' => $teacher->id]);

    $subject = Subject::factory()->create();
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    Booking::factory()->create([
        'student_id' => $student->id,
        'teacher_id' => $teacherProfile->id,
        'subject_id' => $subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.dashboard'));

    $response->assertSuccessful();
    expect($response->viewData('upcomingBookingsList'))->not->toBeEmpty();
});

it('prevents non-admin from accessing admin dashboard', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('prevents non-teacher from accessing teacher dashboard', function () {
    $student = User::factory()->create();
    $student->assignRole('student');

    $this->actingAs($student)
        ->get(route('teacher.dashboard'))
        ->assertForbidden();
});

it('prevents non-student from accessing student dashboard', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('teacher');

    $this->actingAs($teacher)
        ->get(route('student.dashboard'))
        ->assertForbidden();
});
