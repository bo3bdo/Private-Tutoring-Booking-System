@props(['active', 'badge' => null])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-dark-blue-600 dark:border-dark-blue-400 text-sm font-medium leading-5 text-gray-900 dark:text-white focus:outline-none focus:border-dark-blue-700 dark:focus:border-dark-blue-300 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-700 dark:focus:text-gray-200 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span>{{ $slot }}</span>
    @if($badge && $badge > 0)
        <span class="ms-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
            {{ $badge > 99 ? '99+' : $badge }}
        </span>
    @endif
</a>
