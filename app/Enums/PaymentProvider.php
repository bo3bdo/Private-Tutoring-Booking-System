<?php

namespace App\Enums;

enum PaymentProvider: string
{
    case Stripe = 'stripe';
    case BenefitPay = 'benefitpay';

    public function label(): string
    {
        return match ($this) {
            self::Stripe => 'Stripe',
            self::BenefitPay => 'BenefitPay',
        };
    }
}
