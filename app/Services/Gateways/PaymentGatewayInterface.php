<?php

namespace App\Services\Gateways;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout session and return checkout URL and reference
     *
     * @return array{checkout_url: string, provider_reference: string}
     */
    public function createCheckout(\App\Models\Payment $payment): array;

    /**
     * Handle webhook from payment provider
     *
     * @return array{reference: string, status: string, meta?: array}|null
     */
    public function handleWebhook(array $payload): ?array;

    /**
     * Get payment status from provider
     */
    public function getStatus(string $reference): string;
}
