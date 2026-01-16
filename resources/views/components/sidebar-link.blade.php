@props(['active', 'badge' => null])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-dark-blue-700 dark:text-dark-blue-300 bg-dark-blue-50 dark:bg-dark-blue-900/30 transition duration-150 ease-in-out'
            : 'flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @isset($icon)
        <div class="flex-shrink-0">
            {{ $icon }}
        </div>
    @endisset
    <span class="flex-1">{{ $slot }}</span>
    @if($badge && $badge > 0)
        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
            {{ $badge > 99 ? '99+' : $badge }}
        </span>
    @endif
</a>
