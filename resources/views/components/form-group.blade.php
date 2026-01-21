@props(['label', 'name', 'required' => false, 'help' => null, 'error' => null])

<div class="space-y-2">
    @if($label)
        <x-input-label :for="$name" :value="$label" />
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    @endif
    
    <div class="relative">
        {{ $slot }}
        
        @if($help)
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="{{ $name }}-help">
                {{ $help }}
            </p>
        @endif
        
        @if($error)
            <x-input-error :messages="[$error]" class="mt-1" />
        @endif
    </div>
</div>
