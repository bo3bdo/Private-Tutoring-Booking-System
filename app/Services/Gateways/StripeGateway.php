<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function createCheckout(Payment $payment): array
    {
        $booking = $payment->booking;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($payment->currency),
                    'product_data' => [
                        'name' => "Lesson: {$booking->subject->name}",
                        'description' => "Booking with {$booking->teacher->user->name}",
                    ],
                    'unit_amount' => (int) ($payment->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payments.stripe.success', ['payment' => $payment->id]),
            'cancel_url' => route('payments.stripe.cancel', ['payment' => $payment->id]),
            'metadata' => [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
            ],
        ]);

        return [
            'checkout_url' => $session->url,
            'provider_reference' => $session->id,
        ];
    }

    public function handleWebhook(array $payload): ?array
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        $sigHeader = $payload['headers']['stripe-signature'] ?? null;

        if (! $sigHeader || ! $endpointSecret) {
            Log::warning('Stripe webhook signature missing');

            return null;
        }

        try {
            $event = Webhook::constructEvent(
                $payload['body'],
                $sigHeader,
                $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);

            return null;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            return [
                'reference' => $session->id,
                'status' => 'succeeded',
                'meta' => [
                    'stripe_event_id' => $event->id,
                    'customer_email' => $session->customer_email ?? null,
                ],
            ];
        }

        return null;
    }

    public function getStatus(string $reference): string
    {
        try {
            $session = Session::retrieve($reference);

            return match ($session->payment_status) {
                'paid' => 'succeeded',
                'unpaid' => 'pending',
                default => 'failed',
            };
        } catch (\Exception $e) {
            Log::error('Stripe getStatus error', ['error' => $e->getMessage()]);

            return 'failed';
        }
    }
}
