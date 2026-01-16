@props(['active', 'badge' => null])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-dark-blue-600 dark:border-dark-blue-400 text-start text-base font-medium text-dark-blue-700 dark:text-dark-blue-300 bg-dark-blue-50 dark:bg-dark-blue-900/30 focus:outline-none focus:text-dark-blue-800 dark:focus:text-dark-blue-200 focus:bg-dark-blue-100 dark:focus:bg-dark-blue-900/50 focus:border-dark-blue-700 dark:focus:border-dark-blue-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center justify-between">
        <span>{{ $slot }}</span>
        @if($badge && $badge > 0)
            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ $badge > 99 ? '99+' : $badge }}
            </span>
        @endif
    </div>
</a>
