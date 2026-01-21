@props(['id', 'title', 'message', 'confirmText' => __('common.Confirm'), 'cancelText' => __('common.Cancel'), 'type' => 'danger'])

@php
$buttonClasses = [
    'danger' => 'btn-danger',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
    'info' => 'bg-blue-600 hover:bg-blue-700 text-white',
];
$buttonClass = $buttonClasses[$type] ?? $buttonClasses['danger'];
@endphp

<div 
    x-data="{ open: false }"
    @open-dialog.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="open = false"
        ></div>

        <!-- Modal panel -->
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            @click.outside="open = false"
        >
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <form method="POST" action="{{ $attributes->get('action') }}" class="inline">
                    @csrf
                    @if($attributes->has('method'))
                        @method($attributes->get('method'))
                    @endif
                    <button type="submit" class="{{ $buttonClass }} w-full sm:w-auto">
                        {{ $confirmText }}
                    </button>
                </form>
                <button 
                    type="button"
                    @click="open = false"
                    class="btn-secondary w-full sm:w-auto"
                >
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
