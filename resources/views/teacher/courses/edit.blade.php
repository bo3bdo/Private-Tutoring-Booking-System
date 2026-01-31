<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.courses.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit: {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 md:p-8">
                <form method="POST" action="{{ route('teacher.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6" x-data="{ submitting: false }" x-on:submit="submitting = true">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="subject_id" class="block text-sm font-semibold text-gray-900 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('subject_id') border-red-500 @enderror">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $course->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                            Course Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-image-upload-preview
                        name="thumbnail"
                        label="Thumbnail Image"
                        accept="image/*"
                        :existingImage="$course->thumbnail_path ? Storage::url($course->thumbnail_path) : null"
                        existingImageAlt="Current thumbnail"
                    />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-900 mb-2">
                                Price (BHD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="price" id="price" value="{{ old('price', $course->price) }}" step="0.01" min="0" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-semibold text-gray-900 mb-2">
                                Currency
                            </label>
                            <input type="text" name="currency" id="currency" value="{{ old('currency', $course->currency) }}" maxlength="3"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('currency') border-red-500 @enderror">
                            @error('currency')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Published</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('teacher.courses.index') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition">
                            Cancel
                        </a>
                        <button type="submit" :disabled="submitting" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">Update Course</span>
                            <span x-show="submitting" x-cloak class="inline-flex items-center">
                                <svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('common.Loading...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
