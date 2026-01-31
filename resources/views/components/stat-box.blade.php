@props(['label', 'value', 'subtext' => null, 'color' => 'gray', 'icon' => null, 'percentage' => null])

@php
$bgColors = [
    'gray' => 'bg-gray-50 dark:bg-gray-700/50',
    'blue' => 'bg-blue-50 dark:bg-blue-900/20',
    'green' => 'bg-green-50 dark:bg-green-900/20',
    'red' => 'bg-red-50 dark:bg-red-900/20',
    'purple' => 'bg-purple-50 dark:bg-purple-900/20',
    'yellow' => 'bg-yellow-50 dark:bg-yellow-900/20',
];
$textColors = [
    'gray' => 'text-gray-700 dark:text-gray-300',
    'blue' => 'text-blue-700 dark:text-blue-300',
    'green' => 'text-green-700 dark:text-green-300',
    'red' => 'text-red-700 dark:text-red-300',
    'purple' => 'text-purple-700 dark:text-purple-300',
    'yellow' => 'text-yellow-700 dark:text-yellow-300',
];
@endphp

<div class="p-4 {{ $bgColors[$color] ?? $bgColors['gray'] }} rounded-lg border border-gray-200 dark:border-gray-600">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">
                {{ $label }}
            </p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                {{ $value }}
            </p>
            @if($subtext)
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $subtext }}
                </p>
            @endif
        </div>
        @if($percentage)
            <div class="flex-shrink-0 {{ $textColors[$color] ?? $textColors['gray'] }}">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8L5.257 19.393A2 2 0 005 18.07V5a2 2 0 012-2h14a2 2 0 012 2v10"></path>
                </svg>
            </div>
        @elseif($icon)
            <div class="flex-shrink-0">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
