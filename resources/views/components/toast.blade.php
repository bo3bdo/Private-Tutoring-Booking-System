@props(['type' => 'info', 'message', 'duration' => 5000])

@php
$types = [
    'success' => ['bg' => 'bg-green-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'error' => ['bg' => 'bg-red-500', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'warning' => ['bg' => 'bg-yellow-500', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    'info' => ['bg' => 'bg-blue-500', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
];
$config = $types[$type] ?? $types['info'];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    class="fixed {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} top-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
    role="alert"
    aria-live="polite"
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 {{ $config['bg'] }} text-white rounded-full p-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                </svg>
            </div>
            <div class="ms-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $message }}
                </p>
            </div>
            <div class="ms-4 flex-shrink-0 flex">
                <button 
                    @click="show = false"
                    class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                    aria-label="{{ __('common.Close') }}"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
