@props([
    'action' => '',
    'method' => 'POST',
    'enctype' => null,
    'submitText' => null,
    'loadingText' => null,
    'buttonClass' => '',
    'variant' => 'primary',
])

@php
    $submitText = $submitText ?? __('common.Submit');
    $loadingText = $loadingText ?? __('common.Loading...');

    $variants = [
        'primary' => 'bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500',
        'secondary' => 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600 border-2 border-slate-300 dark:border-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-lg',
        'success' => 'bg-green-600 text-white hover:bg-green-700 shadow-lg',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<form
    action="{{ $action }}"
    method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    @if($enctype) enctype="{{ $enctype }}" @endif
    x-data="{ submitting: false }"
    x-on:submit="submitting = true"
    {{ $attributes }}
>
    @if(!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif
    @csrf

    {{ $slot }}

    @if(!isset($customButton) || !$customButton)
        <button
            type="submit"
            :disabled="submitting"
            class="inline-flex items-center justify-center font-semibold rounded-xl px-4 py-2.5 text-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed {{ $variantClass }} {{ $buttonClass }}"
        >
            <span x-show="!submitting">{{ $submitText }}</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center">
                <svg class="w-4 h-4 animate-spin me-2" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ $loadingText }}
            </span>
        </button>
    @endif
</form>
