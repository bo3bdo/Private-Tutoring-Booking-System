@props([
    'label',
    'value',
    'icon' => null,
    'trend' => null,
    'trendUp' => true,
    'color' => 'blue',
    'href' => null,
    'description' => null
])

@php
$colorClasses = match($color) {
    'blue' => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'icon' => 'text-blue-600 dark:text-blue-400',
        'ring' => 'ring-blue-600/20 dark:ring-blue-400/20',
        'accent' => 'from-blue-500 to-blue-600',
    ],
    'green' => [
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'icon' => 'text-green-600 dark:text-green-400',
        'ring' => 'ring-green-600/20 dark:ring-green-400/20',
        'accent' => 'from-green-500 to-green-600',
    ],
    'yellow' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'icon' => 'text-yellow-600 dark:text-yellow-400',
        'ring' => 'ring-yellow-600/20 dark:ring-yellow-400/20',
        'accent' => 'from-yellow-500 to-yellow-600',
    ],
    'red' => [
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'icon' => 'text-red-600 dark:text-red-400',
        'ring' => 'ring-red-600/20 dark:ring-red-400/20',
        'accent' => 'from-red-500 to-red-600',
    ],
    'purple' => [
        'bg' => 'bg-purple-50 dark:bg-purple-900/20',
        'icon' => 'text-purple-600 dark:text-purple-400',
        'ring' => 'ring-purple-600/20 dark:ring-purple-400/20',
        'accent' => 'from-purple-500 to-purple-600',
    ],
    'indigo' => [
        'bg' => 'bg-indigo-50 dark:bg-indigo-900/20',
        'icon' => 'text-indigo-600 dark:text-indigo-400',
        'ring' => 'ring-indigo-600/20 dark:ring-indigo-400/20',
        'accent' => 'from-indigo-500 to-indigo-600',
    ],
    'pink' => [
        'bg' => 'bg-pink-50 dark:bg-pink-900/20',
        'icon' => 'text-pink-600 dark:text-pink-400',
        'ring' => 'ring-pink-600/20 dark:ring-pink-400/20',
        'accent' => 'from-pink-500 to-pink-600',
    ],
    default => [
        'bg' => 'bg-gray-50 dark:bg-gray-800',
        'icon' => 'text-gray-600 dark:text-gray-400',
        'ring' => 'ring-gray-600/20 dark:ring-gray-400/20',
        'accent' => 'from-gray-500 to-gray-600',
    ],
};

$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    {{ $href ? "href={$href}" : '' }}
    {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-xl bg-white dark:bg-gray-800 p-6 shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-700/50 transition-all duration-300 ' . ($href ? 'hover:shadow-lg hover:-translate-y-1 hover:ring-2 ' . $colorClasses['ring'] . ' cursor-pointer' : '')]) }}
>
    {{-- Gradient accent line at top --}}
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $colorClasses['accent'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $label }}
            </p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ $value }}
            </p>

            @if($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $description }}
            </p>
            @endif

            @if($trend)
            <div class="mt-2 flex items-center gap-1">
                @if($trendUp)
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $trend }}</span>
                @else
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                </svg>
                <span class="text-sm font-medium text-red-600 dark:text-red-400">{{ $trend }}</span>
                @endif
            </div>
            @endif
        </div>

        @if($icon)
        <div class="flex-shrink-0 rounded-lg {{ $colorClasses['bg'] }} p-3 transition-transform duration-300 group-hover:scale-110">
            <div class="{{ $colorClasses['icon'] }}">
                {{ $icon }}
            </div>
        </div>
        @endif
    </div>

    {{-- Slot for additional content --}}
    @if($slot->isNotEmpty())
    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
        {{ $slot }}
    </div>
    @endif
</{{ $tag }}>
