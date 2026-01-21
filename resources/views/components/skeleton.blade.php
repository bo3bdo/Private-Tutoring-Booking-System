@props(['variant' => 'text', 'lines' => 1, 'width' => 'full'])

@php
$widths = [
    'full' => 'w-full',
    '3/4' => 'w-3/4',
    '1/2' => 'w-1/2',
    '1/4' => 'w-1/4',
];
$widthClass = $widths[$width] ?? $widths['full'];
@endphp

@if($variant === 'text')
    <div class="animate-pulse space-y-2">
        @for($i = 0; $i < $lines; $i++)
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded {{ $widthClass }} {{ $i === $lines - 1 && $width !== 'full' ? 'w-3/4' : '' }}"></div>
        @endfor
    </div>
@elseif($variant === 'card')
    <div class="animate-pulse bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-4"></div>
        <div class="space-y-2">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-4/6"></div>
        </div>
    </div>
@elseif($variant === 'avatar')
    <div class="animate-pulse">
        <div class="rounded-full bg-gray-200 dark:bg-gray-700 {{ $attributes->get('class', 'w-10 h-10') }}"></div>
    </div>
@elseif($variant === 'button')
    <div class="animate-pulse">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-button {{ $widthClass }}"></div>
    </div>
@endif
