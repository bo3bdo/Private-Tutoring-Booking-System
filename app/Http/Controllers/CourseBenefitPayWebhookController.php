<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Services\CoursePurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CourseBenefitPayWebhookController extends Controller
{
    public function __construct(
        protected CoursePurchaseService $purchaseService
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->all();

        // Verify webhook signature
        $webhookSecret = config('services.benefitpay.course_webhook_secret');
        if (! $webhookSecret) {
            Log::error('BenefitPay course webhook secret not configured');

            return response()->json(['error' => 'Webhook not configured'], 500);
        }

        try {
            // Verify signature (implement based on BenefitPay documentation)
            // For now, simplified version

            if (! isset($payload['reference']) || ! isset($payload['status'])) {
                Log::warning('Invalid BenefitPay webhook payload', ['payload' => $payload]);

                return response()->json(['error' => 'Invalid payload'], 400);
            }

            $reference = $payload['reference'];
            $status = $payload['status'];

            if ($status === 'succeeded' || $status === 'completed') {
                $this->purchaseService->confirmPurchase($reference, PaymentProvider::BenefitPay);
            } elseif ($status === 'failed' || $status === 'cancelled') {
                $this->purchaseService->markPaymentFailed($reference, PaymentProvider::BenefitPay);
            }

            return response()->json(['received' => true]);
        } catch (\Exception $e) {
            Log::error('BenefitPay course webhook error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
