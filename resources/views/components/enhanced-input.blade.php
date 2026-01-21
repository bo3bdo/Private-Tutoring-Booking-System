@props(['name', 'label', 'type' => 'text', 'required' => false, 'help' => null, 'value' => null, 'placeholder' => null, 'disabled' => false, 'autofocus' => false])

<div class="space-y-2" x-data="{ 
    value: @js($value ?? old($name)),
    error: @js($errors->first($name)),
    touched: false,
    validate() {
        this.touched = true;
        if (this.$refs.input.checkValidity()) {
            this.error = null;
        }
    }
}" x-init="
    $watch('value', () => {
        if (touched) validate();
    });
">
    @if($label)
        <x-input-label :for="$name" :value="$label" />
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    @endif
    
    <div class="relative">
        <input
            x-ref="input"
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            :value="value"
            @input="value = $event.target.value; validate()"
            @blur="validate()"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($autofocus) autofocus @endif
            :class="{
                'border-red-500 dark:border-red-400 focus:ring-red-500 dark:focus:ring-red-400': error && touched,
                'border-gray-300 dark:border-gray-600 focus:ring-dark-blue-500 dark:focus:ring-dark-blue-400': !error || !touched
            }"
            class="block w-full rounded-lg border shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-0 dark:bg-gray-700 dark:text-white"
            aria-describedby="{{ $name }}-help {{ $name }}-error"
        >
        
        <div x-show="error && touched" x-transition class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} flex items-center pr-3 pointer-events-none">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
    
    @if($help)
        <p class="text-xs text-gray-500 dark:text-gray-400" id="{{ $name }}-help">
            {{ $help }}
        </p>
    @endif
    
    <div x-show="error && touched" x-transition id="{{ $name }}-error" class="text-sm text-red-600 dark:text-red-400">
        <span x-text="error"></span>
    </div>
    
    @if($errors->has($name))
        <x-input-error :messages="$errors->get($name)" />
    @endif
</div>
