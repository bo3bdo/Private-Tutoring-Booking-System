<?php

namespace App\Services;

use App\Enums\PaymentIntentPurpose;
use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CoursePurchase;
use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\User;
use App\Services\Gateways\BenefitPayGateway;
use App\Services\Gateways\PaymentGatewayInterface;
use App\Services\Gateways\StripeGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoursePurchaseService
{
    protected array $gateways = [];

    public function __construct(
        protected NotificationService $notificationService
    ) {
        $this->gateways = [
            PaymentProvider::Stripe->value => new StripeGateway,
            PaymentProvider::BenefitPay->value => new BenefitPayGateway,
        ];
    }

    public function startPurchase(Course $course, User $student, PaymentProvider $provider): Payment
    {
        return DB::transaction(function () use ($course, $student, $provider) {
            // Check if already enrolled
            if ($course->isEnrolledBy($student)) {
                throw new \Exception('You are already enrolled in this course.');
            }

            // Check if there's a pending purchase
            $existingPayment = Payment::whereHas('paymentIntent', function ($query) use ($course, $student) {
                $query->where('course_id', $course->id)
                    ->where('student_id', $student->id);
            })
                ->whereIn('status', [PaymentStatus::Initiated, PaymentStatus::Pending])
                ->first();

            if ($existingPayment) {
                return $existingPayment;
            }

            // Create payment record (no booking_id for courses)
            $payment = Payment::create([
                'student_id' => $student->id,
                'provider' => $provider,
                'amount' => $course->price,
                'currency' => $course->currency ?? 'BHD',
                'status' => PaymentStatus::Initiated,
            ]);

            // Create course checkout (custom logic since gateways expect booking)
            $checkout = $this->createCourseCheckout($payment, $course, $provider);

            // Refresh payment to get latest status
            $payment->refresh();

            // Update payment with checkout details (unless test mode already completed it)
            if ($payment->status === PaymentStatus::Initiated) {
                $payment->update([
                    'checkout_url' => $checkout['checkout_url'],
                    'provider_reference' => $checkout['provider_reference'],
                    'status' => PaymentStatus::Pending,
                ]);
            }

            // Create payment intent record (only if not already created in test mode)
            if (! PaymentIntent::where('payment_id', $payment->id)->exists()) {
                PaymentIntent::create([
                    'payment_id' => $payment->id,
                    'purpose' => PaymentIntentPurpose::Course,
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                    'provider' => $provider,
                    'provider_reference' => $checkout['provider_reference'],
                ]);
            }

            return $payment;
        });
    }

    public function confirmPurchase(string $providerReference, PaymentProvider $provider): void
    {
        DB::transaction(function () use ($providerReference, $provider) {
            // Find payment intent
            $paymentIntent = PaymentIntent::where('provider_reference', $providerReference)
                ->where('provider', $provider)
                ->lockForUpdate()
                ->first();

            if (! $paymentIntent) {
                Log::warning('PaymentIntent not found for webhook', ['reference' => $providerReference]);

                return;
            }

            // Lock payment
            $payment = Payment::where('id', $paymentIntent->payment_id)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                Log::warning('Payment not found for PaymentIntent', ['payment_intent_id' => $paymentIntent->id]);

                return;
            }

            // Idempotency check
            if ($payment->status === PaymentStatus::Succeeded) {
                Log::info('Course purchase already processed', ['payment_id' => $payment->id]);

                return;
            }

            // Update payment status
            $payment->update([
                'status' => PaymentStatus::Succeeded,
                'paid_at' => now(),
            ]);

            // Create course purchase (unique constraint will prevent duplicates)
            try {
                CoursePurchase::create([
                    'course_id' => $paymentIntent->course_id,
                    'student_id' => $paymentIntent->student_id,
                    'payment_id' => $payment->id,
                    'purchased_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('CoursePurchase already exists', [
                    'course_id' => $paymentIntent->course_id,
                    'student_id' => $paymentIntent->student_id,
                ]);
            }

            // Create enrollment (unique constraint will prevent duplicates)
            try {
                CourseEnrollment::create([
                    'course_id' => $paymentIntent->course_id,
                    'student_id' => $paymentIntent->student_id,
                    'enrolled_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('CourseEnrollment already exists', [
                    'course_id' => $paymentIntent->course_id,
                    'student_id' => $paymentIntent->student_id,
                ]);
            }

            // Send notifications
            $course = $paymentIntent->course;
            $student = $paymentIntent->student;

            $this->notificationService->sendCourseEnrolledToStudent($course, $student);
            $this->notificationService->sendCourseEnrolledToTeacher($course, $student);
        });
    }

    public function markPaymentFailed(string $providerReference, PaymentProvider $provider): void
    {
        DB::transaction(function () use ($providerReference, $provider) {
            $paymentIntent = PaymentIntent::where('provider_reference', $providerReference)
                ->where('provider', $provider)
                ->first();

            if ($paymentIntent) {
                $payment = $paymentIntent->payment;
                if ($payment && $payment->status !== PaymentStatus::Succeeded) {
                    $payment->update([
                        'status' => PaymentStatus::Failed,
                    ]);
                }
            }
        });
    }

    protected function createCourseCheckout(Payment $payment, Course $course, PaymentProvider $provider): array
    {
        // Create a temporary booking-like object for gateway compatibility
        // Or create custom checkout logic for courses
        $gateway = $this->getGateway($provider);

        // For Stripe, create session directly
        if ($provider === PaymentProvider::Stripe) {
            return $this->createStripeCourseCheckout($payment, $course);
        }

        // For BenefitPay, create checkout directly
        if ($provider === PaymentProvider::BenefitPay) {
            return $this->createBenefitPayCourseCheckout($payment, $course);
        }

        throw new \Exception("Unsupported payment provider for courses: {$provider->value}");
    }

    protected function createStripeCourseCheckout(Payment $payment, Course $course): array
    {
        // For testing/development: if in local environment, auto-complete payment
        if (app()->environment('local') || config('app.debug')) {
            return $this->createTestCourseCheckout($payment, $course);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($payment->currency),
                    'product_data' => [
                        'name' => "Course: {$course->title}",
                        'description' => $course->description ?: "Recorded course by {$course->teacher->name}",
                    ],
                    'unit_amount' => (int) ($payment->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('student.my-courses.index').'?payment=success',
            'cancel_url' => route('student.courses.show', $course->slug).'?payment=cancelled',
            'metadata' => [
                'payment_id' => $payment->id,
                'course_id' => $course->id,
                'student_id' => $payment->student_id,
                'purpose' => 'course',
            ],
        ]);

        return [
            'checkout_url' => $session->url,
            'provider_reference' => $session->id,
        ];
    }

    protected function createBenefitPayCourseCheckout(Payment $payment, Course $course): array
    {
        // For testing/development: if in local environment, auto-complete payment
        if (app()->environment('local') || config('app.debug')) {
            return $this->createTestCourseCheckout($payment, $course);
        }

        $apiUrl = config('services.benefitpay.api_url', 'https://api.benefitpay.com');
        $merchantId = config('services.benefitpay.merchant_id');
        $apiKey = config('services.benefitpay.api_key');

        if (! $merchantId || ! $apiKey) {
            throw new \Exception('BenefitPay credentials not configured.');
        }

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->post("{$apiUrl}/checkout", [
            'merchant_id' => $merchantId,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'order_id' => "course_{$course->id}_{$payment->student_id}",
            'description' => "Course: {$course->title}",
            'success_url' => route('student.my-courses.index').'?payment=success',
            'cancel_url' => route('student.courses.show', $course->slug).'?payment=cancelled',
            'metadata' => [
                'payment_id' => $payment->id,
                'course_id' => $course->id,
                'student_id' => $payment->student_id,
                'purpose' => 'course',
            ],
        ]);

        if (! $response->successful()) {
            \Illuminate\Support\Facades\Log::error('BenefitPay course checkout creation failed', [
                'response' => $response->body(),
            ]);
            throw new \Exception('Failed to create BenefitPay checkout');
        }

        $data = $response->json();

        return [
            'checkout_url' => $data['checkout_url'] ?? $data['redirect_url'] ?? '',
            'provider_reference' => $data['transaction_id'] ?? $data['reference'] ?? '',
        ];
    }

    /**
     * Create test checkout that auto-completes payment (for development/testing)
     */
    protected function createTestCourseCheckout(Payment $payment, Course $course): array
    {
        // Auto-complete the payment immediately (no nested transaction - already in one)
        $providerReference = 'test_'.uniqid();

        $payment->update([
            'status' => PaymentStatus::Succeeded,
            'paid_at' => now(),
            'provider_reference' => $providerReference,
            'checkout_url' => route('student.my-courses.index').'?payment=success',
        ]);

        // Create payment intent (will be checked in startPurchase to avoid duplicate)
        PaymentIntent::create([
            'payment_id' => $payment->id,
            'purpose' => PaymentIntentPurpose::Course,
            'course_id' => $course->id,
            'student_id' => $payment->student_id,
            'provider' => $payment->provider,
            'provider_reference' => $providerReference,
        ]);

        // Create course purchase
        CoursePurchase::firstOrCreate(
            [
                'course_id' => $course->id,
                'student_id' => $payment->student_id,
            ],
            [
                'payment_id' => $payment->id,
                'purchased_at' => now(),
            ]
        );

        // Create enrollment
        CourseEnrollment::firstOrCreate(
            [
                'course_id' => $course->id,
                'student_id' => $payment->student_id,
            ],
            [
                'enrolled_at' => now(),
            ]
        );

        // Send notifications (queue them to avoid blocking)
        $student = $payment->student;
        $this->notificationService->sendCourseEnrolledToStudent($course, $student);
        $this->notificationService->sendCourseEnrolledToTeacher($course, $student);

        return [
            'checkout_url' => route('student.my-courses.index').'?payment=success',
            'provider_reference' => $providerReference,
        ];
    }

    protected function getGateway(PaymentProvider $provider): PaymentGatewayInterface
    {
        if (! isset($this->gateways[$provider->value])) {
            throw new \Exception("Gateway not found for provider: {$provider->value}");
        }

        return $this->gateways[$provider->value];
    }
}
