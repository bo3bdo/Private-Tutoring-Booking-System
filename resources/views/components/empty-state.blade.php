@props(['title', 'description' => null, 'icon' => null, 'action' => null, 'actionLabel' => null])

<div class="text-center py-12 px-4">
    @if($icon)
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
            {{ $icon }}
        </div>
    @else
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    @endif
    
    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $title }}
    </h3>
    
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $description }}
        </p>
    @endif
    
    @if($action && $actionLabel)
        <div class="mt-6">
            <a href="{{ $action }}" class="btn-primary">
                {{ $actionLabel }}
            </a>
        </div>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
