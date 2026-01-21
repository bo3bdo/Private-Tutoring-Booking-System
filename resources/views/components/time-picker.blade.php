@props(['name', 'label', 'value' => null, 'required' => false, 'help' => null])

<div class="space-y-2" x-data="{ value: @js($value ?? old($name)) }">
    @if($label)
        <x-input-label :for="$name" :value="$label" />
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    @endif
    
    <div class="relative">
        <input
            type="time"
            id="{{ $name }}"
            name="{{ $name }}"
            x-model="value"
            @if($required) required @endif
            class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-dark-blue-500 dark:focus:border-dark-blue-400 focus:ring-dark-blue-500 dark:focus:ring-dark-blue-400 transition duration-150"
            aria-describedby="{{ $name }}-help"
        >
    </div>
    
    @if($help)
        <p class="text-xs text-gray-500 dark:text-gray-400" id="{{ $name }}-help">
            {{ $help }}
        </p>
    @endif
    
    @if($errors->has($name))
        <x-input-error :messages="$errors->get($name)" />
    @endif
</div>
