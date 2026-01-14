<?php

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CoursePurchase;
use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\CoursePurchaseService;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->subject = Subject::factory()->create(['is_active' => true]);
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $courseService = app(CourseService::class);

    $this->course = Course::create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'title' => 'Test Course',
        'slug' => $courseService->generateSlug('Test Course'),
        'description' => 'A test course',
        'price' => 25.00,
        'currency' => 'BHD',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $this->purchaseService = app(CoursePurchaseService::class);
});

it('prevents duplicate course purchases', function () {
    // Create first purchase
    $payment1 = Payment::create([
        'student_id' => $this->student->id,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Succeeded,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_1',
        'booking_id' => null,
    ]);

    CoursePurchase::create([
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'payment_id' => $payment1->id,
        'purchased_at' => now(),
    ]);

    CourseEnrollment::create([
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'enrolled_at' => now(),
    ]);

    // Try to create duplicate purchase
    $payment2 = Payment::create([
        'student_id' => $this->student->id,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Succeeded,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_2',
        'booking_id' => null,
    ]);

    expect(function () use ($payment2) {
        CoursePurchase::create([
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'payment_id' => $payment2->id,
            'purchased_at' => now(),
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('enrolls student after successful payment', function () {
    $payment = Payment::create([
        'student_id' => $this->student->id,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Initiated,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_test',
        'booking_id' => null,
    ]);

    $paymentIntent = PaymentIntent::create([
        'payment_id' => $payment->id,
        'purpose' => 'course',
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_test',
    ]);

    // Simulate webhook confirmation
    $this->purchaseService->confirmPurchase($paymentIntent->provider_reference, PaymentProvider::Stripe);

    $this->assertDatabaseHas('course_enrollments', [
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
    ]);

    $this->assertDatabaseHas('course_purchases', [
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'payment_id' => $payment->id,
    ]);

    expect($payment->fresh()->status)->toBe(PaymentStatus::Succeeded);
});

it('handles webhook idempotency for course purchases', function () {
    $payment = Payment::create([
        'student_id' => $this->student->id,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Initiated,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_idempotent',
        'booking_id' => null,
    ]);

    $paymentIntent = PaymentIntent::create([
        'payment_id' => $payment->id,
        'purpose' => 'course',
        'course_id' => $this->course->id,
        'student_id' => $this->student->id,
        'provider' => 'stripe',
        'provider_reference' => 'stripe_ref_idempotent',
    ]);

    // First webhook call
    $this->purchaseService->confirmPurchase($paymentIntent->provider_reference, PaymentProvider::Stripe);

    $enrollmentCount1 = CourseEnrollment::where('course_id', $this->course->id)
        ->where('student_id', $this->student->id)
        ->count();

    // Second webhook call (should be idempotent)
    $this->purchaseService->confirmPurchase($paymentIntent->provider_reference, PaymentProvider::Stripe);

    $enrollmentCount2 = CourseEnrollment::where('course_id', $this->course->id)
        ->where('student_id', $this->student->id)
        ->count();

    expect($enrollmentCount1)->toBe(1);
    expect($enrollmentCount2)->toBe(1); // Should still be 1, not 2
});
