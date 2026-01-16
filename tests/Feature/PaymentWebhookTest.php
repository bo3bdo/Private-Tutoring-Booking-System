<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
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
        'status' => \App\Enums\SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'status' => BookingStatus::AwaitingPayment,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $this->paymentService = app(PaymentService::class);
});

it('handles stripe webhook success and confirms booking', function () {
    Notification::fake();

    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::Stripe,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Pending,
        'provider_reference' => 'stripe_test_ref_123',
    ]);

    $webhookPayload = [
        'body' => json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'stripe_test_ref_123',
                    'payment_status' => 'paid',
                ],
            ],
        ]),
        'headers' => [
            'stripe-signature' => 'test_signature',
        ],
    ];

    // Mock the gateway to return success
    $mockGateway = Mockery::mock(\App\Services\Gateways\StripeGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'stripe_test_ref_123',
            'status' => 'succeeded',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\StripeGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::Stripe, $webhookPayload);

    expect($payment->fresh()->status)->toBe(PaymentStatus::Succeeded);
    expect($payment->fresh()->paid_at)->not->toBeNull();
    expect($this->booking->fresh()->status)->toBe(BookingStatus::Confirmed);
});

it('handles stripe webhook failure', function () {
    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::Stripe,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Pending,
        'provider_reference' => 'stripe_test_ref_fail',
    ]);

    $webhookPayload = [
        'body' => json_encode([
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'stripe_test_ref_fail',
                ],
            ],
        ]),
        'headers' => [
            'stripe-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\StripeGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'stripe_test_ref_fail',
            'status' => 'failed',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\StripeGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::Stripe, $webhookPayload);

    expect($payment->fresh()->status)->toBe(PaymentStatus::Failed);
    expect($this->booking->fresh()->status)->toBe(BookingStatus::AwaitingPayment);
});

it('handles benefitpay webhook success and confirms booking', function () {
    Notification::fake();

    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::BenefitPay,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Pending,
        'provider_reference' => 'benefitpay_test_ref_123',
    ]);

    $webhookPayload = [
        'body' => json_encode([
            'transaction_id' => 'benefitpay_test_ref_123',
            'status' => 'SUCCESS',
        ]),
        'headers' => [
            'x-benefitpay-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\BenefitPayGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'benefitpay_test_ref_123',
            'status' => 'succeeded',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\BenefitPayGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::BenefitPay, $webhookPayload);

    expect($payment->fresh()->status)->toBe(PaymentStatus::Succeeded);
    expect($payment->fresh()->paid_at)->not->toBeNull();
    expect($this->booking->fresh()->status)->toBe(BookingStatus::Confirmed);
});

it('handles benefitpay webhook failure', function () {
    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::BenefitPay,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Pending,
        'provider_reference' => 'benefitpay_test_ref_fail',
    ]);

    $webhookPayload = [
        'body' => json_encode([
            'transaction_id' => 'benefitpay_test_ref_fail',
            'status' => 'FAILED',
        ]),
        'headers' => [
            'x-benefitpay-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\BenefitPayGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'benefitpay_test_ref_fail',
            'status' => 'failed',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\BenefitPayGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::BenefitPay, $webhookPayload);

    expect($payment->fresh()->status)->toBe(PaymentStatus::Failed);
    expect($this->booking->fresh()->status)->toBe(BookingStatus::AwaitingPayment);
});

it('handles webhook idempotency - does not process same payment twice', function () {
    Notification::fake();

    $payment = Payment::create([
        'booking_id' => $this->booking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::Stripe,
        'amount' => 25.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Succeeded,
        'provider_reference' => 'stripe_test_ref_idempotent',
        'paid_at' => now(),
    ]);

    $this->booking->update(['status' => BookingStatus::Confirmed]);

    $webhookPayload = [
        'body' => json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'stripe_test_ref_idempotent',
                ],
            ],
        ]),
        'headers' => [
            'stripe-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\StripeGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'stripe_test_ref_idempotent',
            'status' => 'succeeded',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\StripeGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::Stripe, $webhookPayload);

    // Payment should remain succeeded, booking should remain confirmed
    expect($payment->fresh()->status)->toBe(PaymentStatus::Succeeded);
    expect($this->booking->fresh()->status)->toBe(BookingStatus::Confirmed);
});

it('ignores webhook with invalid reference', function () {
    $webhookPayload = [
        'body' => json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'invalid_reference',
                ],
            ],
        ]),
        'headers' => [
            'stripe-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\StripeGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'invalid_reference',
            'status' => 'succeeded',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\StripeGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::Stripe, $webhookPayload);

    // Booking should remain in awaiting payment status
    expect($this->booking->fresh()->status)->toBe(BookingStatus::AwaitingPayment);
});

it('handles webhook for booking that does not require payment', function () {
    $freeSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => \App\Enums\SlotStatus::Booked,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    $freeBooking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $freeSlot->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => $freeSlot->start_at,
        'end_at' => $freeSlot->end_at,
        'lesson_mode' => \App\Enums\LessonMode::Online,
    ]);

    $payment = Payment::create([
        'booking_id' => $freeBooking->id,
        'student_id' => $this->student->id,
        'provider' => PaymentProvider::Stripe,
        'amount' => 0.00,
        'currency' => 'BHD',
        'status' => PaymentStatus::Succeeded,
        'provider_reference' => 'stripe_test_ref_free',
        'paid_at' => now(),
    ]);

    $webhookPayload = [
        'body' => json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'stripe_test_ref_free',
                ],
            ],
        ]),
        'headers' => [
            'stripe-signature' => 'test_signature',
        ],
    ];

    $mockGateway = Mockery::mock(\App\Services\Gateways\StripeGateway::class);
    $mockGateway->shouldReceive('handleWebhook')
        ->once()
        ->andReturn([
            'reference' => 'stripe_test_ref_free',
            'status' => 'succeeded',
            'meta' => [],
        ]);

    app()->instance(\App\Services\Gateways\StripeGateway::class, $mockGateway);

    // Reinstantiate PaymentService to use the mocked gateway
    $paymentService = app(PaymentService::class);
    $paymentService->handleWebhook(PaymentProvider::Stripe, $webhookPayload);

    // Booking should remain confirmed (not change)
    expect($freeBooking->fresh()->status)->toBe(BookingStatus::Confirmed);
});
