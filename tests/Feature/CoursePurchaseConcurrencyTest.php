<?php

use App\Enums\PaymentProvider;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CoursePurchase;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\CoursePurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student1 = User::factory()->create();
    $this->student1->assignRole('student');

    $this->student2 = User::factory()->create();
    $this->student2->assignRole('student');

    $this->subject = Subject::factory()->create();

    $this->course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'price' => 50.00,
        'is_published' => true,
    ]);

    $this->purchaseService = app(CoursePurchaseService::class);
});

it('allows multiple students to purchase the same course', function () {
    // Student 1 purchases the course
    $payment1 = $this->purchaseService->startPurchase(
        $this->course,
        $this->student1,
        PaymentProvider::Stripe
    );

    expect($payment1)->not->toBeNull();
    expect($payment1->student_id)->toBe($this->student1->id);

    // Student 2 purchases the same course
    $payment2 = $this->purchaseService->startPurchase(
        $this->course,
        $this->student2,
        PaymentProvider::Stripe
    );

    expect($payment2)->not->toBeNull();
    expect($payment2->student_id)->toBe($this->student2->id);

    // Both should have separate payments
    expect($payment1->id)->not->toBe($payment2->id);
});

it('prevents duplicate enrollment for the same student', function () {
    // First purchase
    $payment1 = $this->purchaseService->startPurchase(
        $this->course,
        $this->student1,
        PaymentProvider::Stripe
    );

    // Since we're in local/debug mode, payment completes automatically
    expect(CourseEnrollment::where('student_id', $this->student1->id)
        ->where('course_id', $this->course->id)
        ->count())->toBe(1);

    // Try to purchase again
    expect(function () {
        $this->purchaseService->startPurchase(
            $this->course,
            $this->student1,
            PaymentProvider::Stripe
        );
    })->toThrow(\Exception::class, 'already enrolled');
});

it('creates payment with correct amount and currency', function () {
    $payment = $this->purchaseService->startPurchase(
        $this->course,
        $this->student1,
        PaymentProvider::Stripe
    );

    expect($payment)->not->toBeNull();
    expect($payment->amount)->toBe('50.00');
    expect($payment->currency)->toBe('BHD');
    expect($payment->student_id)->toBe($this->student1->id);
});

it('creates purchase and enrollment records on successful payment', function () {
    $payment = $this->purchaseService->startPurchase(
        $this->course,
        $this->student1,
        PaymentProvider::Stripe
    );

    // Check enrollment was created
    expect(CourseEnrollment::where('student_id', $this->student1->id)
        ->where('course_id', $this->course->id)
        ->exists())->toBeTrue();

    // Check purchase was created
    expect(CoursePurchase::where('student_id', $this->student1->id)
        ->where('course_id', $this->course->id)
        ->exists())->toBeTrue();
});

it('tracks unique enrollments per course', function () {
    // Student 1 enrolls
    $this->purchaseService->startPurchase(
        $this->course,
        $this->student1,
        PaymentProvider::Stripe
    );

    // Student 2 enrolls
    $this->purchaseService->startPurchase(
        $this->course,
        $this->student2,
        PaymentProvider::Stripe
    );

    // Should have 2 enrollments
    expect(CourseEnrollment::where('course_id', $this->course->id)->count())->toBe(2);

    // Should have 2 purchases
    expect(CoursePurchase::where('course_id', $this->course->id)->count())->toBe(2);
});
