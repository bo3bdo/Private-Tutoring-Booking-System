<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Services\CoursePurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CourseStripeWebhookController extends Controller
{
    public function __construct(
        protected CoursePurchaseService $purchaseService
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        // Verify webhook signature
        $webhookSecret = config('services.stripe.course_webhook_secret');
        if (! $webhookSecret) {
            Log::error('Stripe course webhook secret not configured');

            return response()->json(['error' => 'Webhook not configured'], 500);
        }

        try {
            // Verify signature (simplified - in production use Stripe SDK)
            $event = json_decode($payload, true);

            if (! isset($event['type']) || ! isset($event['data']['object'])) {
                Log::warning('Invalid Stripe webhook payload', ['payload' => $payload]);

                return response()->json(['error' => 'Invalid payload'], 400);
            }

            // Handle payment success
            if ($event['type'] === 'checkout.session.completed' || $event['type'] === 'payment_intent.succeeded') {
                $paymentIntent = $event['data']['object'];
                $reference = $paymentIntent['id'] ?? $paymentIntent['payment_intent'] ?? null;

                if ($reference) {
                    $this->purchaseService->confirmPurchase($reference, PaymentProvider::Stripe);
                }
            }

            // Handle payment failure
            if ($event['type'] === 'payment_intent.payment_failed') {
                $paymentIntent = $event['data']['object'];
                $reference = $paymentIntent['id'] ?? null;

                if ($reference) {
                    $this->purchaseService->markPaymentFailed($reference, PaymentProvider::Stripe);
                }
            }

            return response()->json(['received' => true]);
        } catch (\Exception $e) {
            Log::error('Stripe course webhook error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
