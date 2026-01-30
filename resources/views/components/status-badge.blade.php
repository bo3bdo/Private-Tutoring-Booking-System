@props(['status', 'size' => 'sm'])

@php
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;

$sizeClasses = match($size) {
    'xs' => 'px-1.5 py-0.5 text-xs',
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-2.5 py-1.5 text-sm',
    'lg' => 'px-3 py-2 text-sm',
    default => 'px-2 py-1 text-xs',
};

// Handle different status types
if ($status instanceof BookingStatus) {
    $label = $status->label();
    $color = $status->color();
} elseif ($status instanceof PaymentStatus) {
    $label = $status->label();
    $color = match($status) {
        PaymentStatus::Initiated => 'gray',
        PaymentStatus::Pending => 'yellow',
        PaymentStatus::Succeeded => 'green',
        PaymentStatus::Failed => 'red',
        PaymentStatus::Refunded => 'purple',
        PaymentStatus::Cancelled => 'red',
    };
} elseif (is_string($status)) {
    $label = ucfirst(str_replace('_', ' ', $status));
    $color = match(strtolower($status)) {
        'active', 'confirmed', 'succeeded', 'approved', 'published' => 'green',
        'pending', 'awaiting_payment', 'draft' => 'yellow',
        'cancelled', 'failed', 'rejected' => 'red',
        'completed' => 'blue',
        'refunded', 'rescheduled' => 'purple',
        'no_show' => 'rose',
        default => 'gray',
    };
} else {
    $label = 'Unknown';
    $color = 'gray';
}

$colorClasses = match($color) {
    'green' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
    'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    'red' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
    'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
    'rose' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
    'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-full {$sizeClasses} {$colorClasses}"]) }}>
    {{ $label }}
</span>
