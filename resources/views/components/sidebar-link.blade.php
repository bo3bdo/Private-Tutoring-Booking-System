@props(['active', 'badge' => null])

@php
$classes = ($active ?? false)
            ? 'group relative flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-dark-blue-700 dark:text-dark-blue-300 bg-gradient-to-r from-dark-blue-50 to-dark-blue-100/50 dark:from-dark-blue-900/30 dark:to-dark-blue-900/10 transition-all duration-200 ease-in-out'
            : 'group relative flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{-- Active indicator bar --}}
    @if($active ?? false)
    <div class="absolute inset-y-2 start-0 w-1 bg-gradient-to-b from-dark-blue-500 to-dark-blue-600 rounded-full"></div>
    @endif

    @isset($icon)
        <div class="flex-shrink-0 transition-transform duration-200 group-hover:scale-110">
            {{ $icon }}
        </div>
    @endisset
    <span class="flex-1">{{ $slot }}</span>
    @if($badge && $badge > 0)
        <span class="inline-flex items-center justify-center min-w-[1.25rem] px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse-soft">
            {{ $badge > 99 ? '99+' : $badge }}
        </span>
    @endif
</a>
