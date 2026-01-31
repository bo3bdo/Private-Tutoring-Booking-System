@props([
    'type' => 'submit',
    'loadingText' => null,
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $loadingText = $loadingText ?? __('common.Loading...');

    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500',
        'secondary' => 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600 border-2 border-slate-300 dark:border-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-lg',
        'success' => 'bg-green-600 text-white hover:bg-green-700 shadow-lg',
    ];

    $sizes = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

{{--
    Usage: Wrap your form with x-data="{ submitting: false }" x-on:submit="submitting = true"
    Then use this button inside the form.

    Example:
    <form x-data="{ submitting: false }" x-on:submit="submitting = true" action="..." method="POST">
        @csrf
        <!-- form fields -->
        <x-button-loading>Submit</x-button-loading>
    </form>
--}}

<button
    type="{{ $type }}"
    x-bind:disabled="submitting"
    {{ $attributes->merge(['class' => "$baseClasses $variantClass $sizeClass"]) }}
>
    <span x-show="!submitting" class="inline-flex items-center">{{ $slot }}</span>
    <span x-show="submitting" x-cloak class="inline-flex items-center">
        <svg class="w-4 h-4 animate-spin me-2" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ $loadingText }}
    </span>
</button>
