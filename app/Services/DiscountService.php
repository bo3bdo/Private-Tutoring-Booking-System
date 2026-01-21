<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\DiscountUsage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DiscountService
{
    public function validateDiscount(string $code, User $user, float $amount): array
    {
        $discount = Discount::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $discount) {
            return [
                'valid' => false,
                'message' => __('common.Invalid discount code'),
            ];
        }

        if ($discount->starts_at && $discount->starts_at->isFuture()) {
            return [
                'valid' => false,
                'message' => __('common.Discount code is not yet active'),
            ];
        }

        if ($discount->expires_at && $discount->expires_at->isPast()) {
            return [
                'valid' => false,
                'message' => __('common.Discount code has expired'),
            ];
        }

        if ($discount->max_uses && $discount->usages()->count() >= $discount->max_uses) {
            return [
                'valid' => false,
                'message' => __('common.Discount code has reached maximum uses'),
            ];
        }

        if ($discount->max_uses_per_user) {
            $userUsageCount = $discount->usages()
                ->where('user_id', $user->id)
                ->count();

            if ($userUsageCount >= $discount->max_uses_per_user) {
                return [
                    'valid' => false,
                    'message' => __('common.You have already used this discount code'),
                ];
            }
        }

        if ($discount->min_amount && $amount < $discount->min_amount) {
            return [
                'valid' => false,
                'message' => __('common.Minimum amount required: :amount', ['amount' => number_format($discount->min_amount, 2)]),
            ];
        }

        $discountAmount = $this->calculateDiscount($discount, $amount);

        return [
            'valid' => true,
            'discount' => $discount,
            'discount_amount' => $discountAmount,
            'final_amount' => $amount - $discountAmount,
        ];
    }

    public function applyDiscount(Discount $discount, User $user, float $amount): DiscountUsage
    {
        return DB::transaction(function () use ($discount, $user, $amount) {
            $discountAmount = $this->calculateDiscount($discount, $amount);

            return DiscountUsage::create([
                'discount_id' => $discount->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'discount_amount' => $discountAmount,
                'final_amount' => $amount - $discountAmount,
            ]);
        });
    }

    protected function calculateDiscount(Discount $discount, float $amount): float
    {
        if ($discount->type === 'percentage') {
            $discountAmount = ($amount * $discount->value) / 100;

            if ($discount->max_discount_amount) {
                $discountAmount = min($discountAmount, $discount->max_discount_amount);
            }

            return round($discountAmount, 2);
        }

        return min($discount->value, $amount);
    }
}
