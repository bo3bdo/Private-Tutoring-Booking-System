@props([
    'count' => 4,
    'cols' => 4
])

@php
$gridCols = match($cols) {
    2 => 'grid-cols-1 sm:grid-cols-2',
    3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
};
@endphp

<div {{ $attributes->merge(['class' => "grid {$gridCols} gap-4"]) }}>
    @for($i = 0; $i < $count; $i++)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700/50 animate-pulse">
        <div class="flex items-start justify-between">
            <div class="flex-1 space-y-3">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
            </div>
            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
        </div>
    </div>
    @endfor
</div>
