@props(['title', 'value', 'icon' => null, 'trend' => null, 'trendPositive' => true, 'color' => 'blue', 'href' => null, 'description' => null])

@php
$colorClasses = [
    'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-900/30', 'border' => 'border-blue-200 dark:border-blue-700/50', 'icon-bg' => 'bg-blue-600', 'text' => 'text-blue-600 dark:text-blue-400', 'hover' => 'hover:border-blue-300 dark:hover:border-blue-600'],
    'green' => ['bg' => 'bg-green-50 dark:bg-green-900/30', 'border' => 'border-green-200 dark:border-green-700/50', 'icon-bg' => 'bg-green-600', 'text' => 'text-green-600 dark:text-green-400', 'hover' => 'hover:border-green-300 dark:hover:border-green-600'],
    'red' => ['bg' => 'bg-red-50 dark:bg-red-900/30', 'border' => 'border-red-200 dark:border-red-700/50', 'icon-bg' => 'bg-red-600', 'text' => 'text-red-600 dark:text-red-400', 'hover' => 'hover:border-red-300 dark:hover:border-red-600'],
    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-900/30', 'border' => 'border-purple-200 dark:border-purple-700/50', 'icon-bg' => 'bg-purple-600', 'text' => 'text-purple-600 dark:text-purple-400', 'hover' => 'hover:border-purple-300 dark:hover:border-purple-600'],
    'yellow' => ['bg' => 'bg-yellow-50 dark:bg-yellow-900/30', 'border' => 'border-yellow-200 dark:border-yellow-700/50', 'icon-bg' => 'bg-yellow-600', 'text' => 'text-yellow-600 dark:text-yellow-400', 'hover' => 'hover:border-yellow-300 dark:hover:border-yellow-600'],
    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/30', 'border' => 'border-emerald-200 dark:border-emerald-700/50', 'icon-bg' => 'bg-emerald-600', 'text' => 'text-emerald-600 dark:text-emerald-400', 'hover' => 'hover:border-emerald-300 dark:hover:border-emerald-600'],
    'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/30', 'border' => 'border-indigo-200 dark:border-indigo-700/50', 'icon-bg' => 'bg-indigo-600', 'text' => 'text-indigo-600 dark:text-indigo-400', 'hover' => 'hover:border-indigo-300 dark:hover:border-indigo-600'],
    'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-900/30', 'border' => 'border-orange-200 dark:border-orange-700/50', 'icon-bg' => 'bg-orange-600', 'text' => 'text-orange-600 dark:text-orange-400', 'hover' => 'hover:border-orange-300 dark:hover:border-orange-600'],
];
$colors = $colorClasses[$color] ?? $colorClasses['blue'];
$trendIcon = $trendPositive ? 'M7 16V4m0 0L3 8m4-4l4 4' : 'M7 8V4m0 0L3 8m4 4l4-4';
$trendColor = $trendPositive ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
@endphp

<div class="group {{ $href ? 'cursor-pointer' : '' }}">
    <a 
        href="{{ $href ?? '#' }}" 
        class="block {{ $colors['bg'] }} rounded-xl sm:rounded-2xl shadow-lg {{ $colors['border'] }} border-2 p-4 sm:p-6 {{ $href ? $colors['hover'] : '' }} hover:shadow-xl transition"
    >
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div class="space-y-1 flex-1">
                <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ $title }}</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white group-hover:{{ $colors['text'] }} transition">{{ $value }}</p>
            </div>
            @if($icon)
                <div class="w-10 h-10 sm:w-12 sm:h-12 {{ $colors['icon-bg'] }} rounded-lg sm:rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition flex-shrink-0">
                    {!! $icon !!}
                </div>
            @endif
        </div>

        @if($description)
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $description }}</p>
        @endif

        @if($trend)
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 {{ $trendColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $trendIcon }}" />
                </svg>
                <span class="text-xs sm:text-sm font-semibold {{ $trendColor }}">
                    {{ $trendPositive ? '+' : '' }}{{ $trend }}%
                </span>
            </div>
        @endif
    </a>
</div>
