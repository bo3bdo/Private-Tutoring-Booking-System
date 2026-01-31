@props([
    'name' => 'image',
    'id' => null,
    'label' => null,
    'accept' => 'image/*',
    'required' => false,
    'existingImage' => null,
    'existingImageAlt' => 'Current image',
])

@php
    $inputId = $id ?? $name;
@endphp

<div x-data="{
    preview: null,
    fileName: null,
    fileSize: null,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            this.clearPreview();
        }
    },
    clearPreview() {
        this.preview = null;
        this.fileName = null;
        this.fileSize = null;
        $refs.fileInput.value = '';
    },
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <!-- Existing Image (for edit forms) -->
    @if($existingImage)
        <div class="mb-3" x-show="!preview">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('common.Current Image') }}:</p>
            <img src="{{ $existingImage }}" alt="{{ $existingImageAlt }}" class="w-32 h-32 object-cover rounded-xl border-2 border-slate-200 dark:border-gray-600">
        </div>
    @endif

    <!-- Preview Container -->
    <div x-show="preview" x-cloak class="mb-3">
        <div class="relative inline-block">
            <img :src="preview" alt="Preview" class="w-32 h-32 object-cover rounded-xl border-2 border-slate-200 dark:border-gray-600">
            <button
                type="button"
                @click="clearPreview()"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition shadow-lg"
                title="{{ __('common.Remove') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="fileName"></p>
        <p class="text-xs text-gray-500 dark:text-gray-500" x-text="fileSize"></p>
    </div>

    <!-- File Input -->
    <div class="relative">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $inputId }}"
            accept="{{ $accept }}"
            x-ref="fileInput"
            @change="handleFileSelect($event)"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 dark:file:bg-gray-700 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-600 cursor-pointer transition']) }}
        >
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
