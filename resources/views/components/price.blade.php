@props([
    'amount',
    'originalAmount' => null,
    'currency' => 'USD',
    'size' => 'md',
    'showCurrency' => true,
    'locale' => null
])

@php
$locale = $locale ?? app()->getLocale();
$amount = floatval($amount);
$originalAmount = $originalAmount ? floatval($originalAmount) : null;
$hasDiscount = $originalAmount && $originalAmount > $amount;
$discountPercent = $hasDiscount ? round((($originalAmount - $amount) / $originalAmount) * 100) : 0;

$currencySymbols = [
    'USD' => '$',
    'EUR' => '€',
    'GBP' => '£',
    'BHD' => 'BD',
    'SAR' => 'SAR',
    'AED' => 'AED',
    'KWD' => 'KD',
    'QAR' => 'QR',
    'OMR' => 'OMR',
];
$symbol = $currencySymbols[$currency] ?? $currency;

$sizeClasses = match($size) {
    'xs' => ['current' => 'text-sm', 'original' => 'text-xs', 'badge' => 'text-xs px-1 py-0.5'],
    'sm' => ['current' => 'text-base', 'original' => 'text-sm', 'badge' => 'text-xs px-1.5 py-0.5'],
    'md' => ['current' => 'text-lg', 'original' => 'text-sm', 'badge' => 'text-xs px-1.5 py-0.5'],
    'lg' => ['current' => 'text-2xl', 'original' => 'text-base', 'badge' => 'text-sm px-2 py-1'],
    'xl' => ['current' => 'text-3xl', 'original' => 'text-lg', 'badge' => 'text-sm px-2 py-1'],
    default => ['current' => 'text-lg', 'original' => 'text-sm', 'badge' => 'text-xs px-1.5 py-0.5'],
};

$formattedAmount = number_format($amount, 2);
$formattedOriginal = $originalAmount ? number_format($originalAmount, 2) : null;
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 flex-wrap']) }}>
    {{-- Current Price --}}
    <span class="{{ $sizeClasses['current'] }} font-bold text-gray-900 dark:text-white">
        @if($showCurrency)
            <span class="text-gray-500 dark:text-gray-400 font-normal">{{ $symbol }}</span>
        @endif
        {{ $formattedAmount }}
    </span>

    {{-- Original Price (if discounted) --}}
    @if($hasDiscount)
        <span class="{{ $sizeClasses['original'] }} text-gray-500 dark:text-gray-400 line-through">
            @if($showCurrency){{ $symbol }}@endif{{ $formattedOriginal }}
        </span>

        {{-- Discount Badge --}}
        <span class="{{ $sizeClasses['badge'] }} bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-full font-medium">
            -{{ $discountPercent }}%
        </span>
    @endif

    {{-- Free Badge --}}
    @if($amount == 0)
        <span class="{{ $sizeClasses['badge'] }} bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full font-medium">
            {{ __('Free') }}
        </span>
    @endif
</div>
