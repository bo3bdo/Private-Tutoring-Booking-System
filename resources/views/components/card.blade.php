@props([
    'hover' => false,
    'gradient' => null,
    'padding' => true
])

@php
$baseClasses = 'relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 ring-1 ring-gray-900/5 dark:ring-gray-700/50 transition-all duration-300';

$hoverClasses = $hover ? 'hover:shadow-lg hover:-translate-y-1 hover:ring-gray-900/10 dark:hover:ring-gray-600/50 cursor-pointer' : 'shadow-sm';

$gradientClasses = match($gradient) {
    'blue' => 'bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800',
    'green' => 'bg-gradient-to-br from-green-50 to-white dark:from-green-900/20 dark:to-gray-800',
    'purple' => 'bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/20 dark:to-gray-800',
    'indigo' => 'bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-900/20 dark:to-gray-800',
    'pink' => 'bg-gradient-to-br from-pink-50 to-white dark:from-pink-900/20 dark:to-gray-800',
    'yellow' => 'bg-gradient-to-br from-yellow-50 to-white dark:from-yellow-900/20 dark:to-gray-800',
    'red' => 'bg-gradient-to-br from-red-50 to-white dark:from-red-900/20 dark:to-gray-800',
    default => '',
};

$paddingClasses = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => trim("$baseClasses $hoverClasses $gradientClasses $paddingClasses")]) }}>
    @isset($header)
    <div class="flex items-center justify-between {{ $padding ? '' : 'px-6 pt-6' }} pb-4 border-b border-gray-100 dark:border-gray-700">
        {{ $header }}
    </div>
    <div class="{{ $padding ? 'pt-4' : 'p-6' }}">
        {{ $slot }}
    </div>
    @else
        {{ $slot }}
    @endisset

    @isset($footer)
    <div class="flex items-center {{ $padding ? '' : 'px-6 pb-6' }} pt-4 border-t border-gray-100 dark:border-gray-700">
        {{ $footer }}
    </div>
    @endisset
</div>
