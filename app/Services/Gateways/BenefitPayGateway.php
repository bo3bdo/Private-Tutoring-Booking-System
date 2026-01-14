<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BenefitPayGateway implements PaymentGatewayInterface
{
    protected string $apiUrl;

    protected ?string $merchantId;

    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.benefitpay.api_url', 'https://api.benefitpay.com');
        $this->merchantId = config('services.benefitpay.merchant_id');
        $this->apiKey = config('services.benefitpay.api_key');
    }

    public function createCheckout(Payment $payment): array
    {
        if (! $this->merchantId || ! $this->apiKey) {
            throw new \Exception('BenefitPay credentials not configured. Please set BENEFITPAY_MERCHANT_ID and BENEFITPAY_API_KEY in your .env file.');
        }

        $booking = $payment->booking;

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/checkout", [
            'merchant_id' => $this->merchantId,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'order_id' => "booking_{$booking->id}",
            'description' => "Lesson: {$booking->subject->name}",
            'success_url' => route('payments.benefitpay.success', ['payment' => $payment->id]),
            'cancel_url' => route('payments.benefitpay.cancel', ['payment' => $payment->id]),
            'metadata' => [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
            ],
        ]);

        if (! $response->successful()) {
            Log::error('BenefitPay checkout creation failed', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);
            throw new \Exception('Failed to create BenefitPay checkout');
        }

        $data = $response->json();

        return [
            'checkout_url' => $data['checkout_url'] ?? $data['redirect_url'] ?? '',
            'provider_reference' => $data['transaction_id'] ?? $data['reference'] ?? '',
        ];
    }

    public function handleWebhook(array $payload): ?array
    {
        $signature = $payload['headers']['x-benefitpay-signature'] ?? null;
        $webhookSecret = config('services.benefitpay.webhook_secret');

        if (! $this->verifySignature($payload['body'], $signature, $webhookSecret)) {
            Log::warning('BenefitPay webhook signature verification failed');

            return null;
        }

        $data = is_string($payload['body']) ? json_decode($payload['body'], true) : $payload['body'];

        $status = match ($data['status'] ?? '') {
            'SUCCESS', 'COMPLETED' => 'succeeded',
            'PENDING' => 'pending',
            'FAILED', 'CANCELLED' => 'failed',
            default => 'pending',
        };

        return [
            'reference' => $data['transaction_id'] ?? $data['reference'] ?? '',
            'status' => $status,
            'meta' => $data,
        ];
    }

    public function getStatus(string $reference): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
            ])->get("{$this->apiUrl}/transactions/{$reference}");

            if (! $response->successful()) {
                return 'failed';
            }

            $data = $response->json();

            return match ($data['status'] ?? '') {
                'SUCCESS', 'COMPLETED' => 'succeeded',
                'PENDING' => 'pending',
                'FAILED', 'CANCELLED' => 'failed',
                default => 'pending',
            };
        } catch (\Exception $e) {
            Log::error('BenefitPay getStatus error', ['error' => $e->getMessage()]);

            return 'failed';
        }
    }

    protected function verifySignature(string $payload, ?string $signature, ?string $secret): bool
    {
        if (! $signature || ! $secret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }
}
