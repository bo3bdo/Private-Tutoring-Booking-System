<?php

namespace App\Services;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Gateways\BenefitPayGateway;
use App\Services\Gateways\PaymentGatewayInterface;
use App\Services\Gateways\StripeGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->gateways = [
            PaymentProvider::Stripe->value => new StripeGateway,
            PaymentProvider::BenefitPay->value => new BenefitPayGateway,
        ];
    }

    public function createPayment(
        Booking $booking,
        PaymentProvider $provider,
        float $amount,
        string $currency = 'BHD'
    ): Payment {
        return DB::transaction(function () use ($booking, $provider, $amount, $currency) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'student_id' => $booking->student_id,
                'provider' => $provider,
                'amount' => $amount,
                'currency' => $currency,
                'status' => PaymentStatus::Initiated,
            ]);

            $gateway = $this->getGateway($provider);
            $checkout = $gateway->createCheckout($payment);

            $payment->update([
                'checkout_url' => $checkout['checkout_url'],
                'provider_reference' => $checkout['provider_reference'],
                'status' => PaymentStatus::Pending,
            ]);

            return $payment;
        });
    }

    public function handleWebhook(PaymentProvider $provider, array $payload): void
    {
        DB::transaction(function () use ($provider, $payload) {
            $gateway = $this->getGateway($provider);
            $event = $gateway->handleWebhook($payload);

            if (! $event || ! isset($event['reference'])) {
                Log::warning('Invalid webhook payload', ['provider' => $provider->value, 'payload' => $payload]);

                return;
            }

            $payment = Payment::where('provider_reference', $event['reference'])
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                Log::warning('Payment not found for webhook', ['reference' => $event['reference']]);

                return;
            }

            if ($payment->status === PaymentStatus::Succeeded && $event['status'] === 'succeeded') {
                Log::info('Payment already processed', ['payment_id' => $payment->id]);

                return;
            }

            if ($event['status'] === 'succeeded') {
                $payment->update([
                    'status' => PaymentStatus::Succeeded,
                    'paid_at' => now(),
                    'meta' => array_merge($payment->meta ?? [], $event['meta'] ?? []),
                ]);

                $booking = $payment->booking;
                if ($booking && $booking->isAwaitingPayment()) {
                    app(BookingService::class)->confirmBooking($booking);
                }
            } elseif ($event['status'] === 'failed') {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'meta' => array_merge($payment->meta ?? [], $event['meta'] ?? []),
                ]);
            } elseif ($event['status'] === 'refunded') {
                $payment->update([
                    'status' => PaymentStatus::Refunded,
                    'meta' => array_merge($payment->meta ?? [], $event['meta'] ?? []),
                ]);
            }
        });
    }

    public function confirmPayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            if ($payment->status !== PaymentStatus::Succeeded) {
                $payment->update([
                    'status' => PaymentStatus::Succeeded,
                    'paid_at' => now(),
                ]);
            }

            $booking = $payment->booking;
            if ($booking && $booking->isAwaitingPayment()) {
                app(BookingService::class)->confirmBooking($booking);
            }
        });
    }

    protected function getGateway(PaymentProvider $provider): PaymentGatewayInterface
    {
        if (! isset($this->gateways[$provider->value])) {
            throw new \Exception("Payment gateway not found: {$provider->value}");
        }

        return $this->gateways[$provider->value];
    }
}
