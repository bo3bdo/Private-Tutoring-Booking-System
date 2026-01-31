<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.resources.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                {{ __('common.Upload Resource') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <form method="POST" action="{{ route('teacher.resources.store') }}" enctype="multipart/form-data"
                    x-data="{
                        submitting: false,
                        resourceType: '',
                        resourceId: '',
                        bookings: {{ Js::from($bookings ?? []) }},
                        courses: {{ Js::from($courses ?? []) }},
                        get items() {
                            if (this.resourceType === 'App\\Models\\Booking') return this.bookings;
                            if (this.resourceType === 'App\\Models\\Course') return this.courses;
                            return [];
                        },
                        getItemLabel(item) {
                            if (this.resourceType === 'App\\Models\\Booking') {
                                return `${item.subject_name} - ${item.student_name} (${item.formatted_date})`;
                            }
                            return item.title;
                        }
                    }"
                    x-on:submit="submitting = true">
                    @csrf
                    @if($resourceable)
                        <input type="hidden" name="resourceable_type" value="{{ get_class($resourceable) }}">
                        <input type="hidden" name="resourceable_id" value="{{ $resourceable->id }}">
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-900">{{ __('common.Uploading resource for:') }} <strong>{{ class_basename($resourceable) }}</strong></p>
                        </div>
                    @else
                        <div class="mb-4">
                            <label for="resourceable_type" class="block text-sm font-semibold text-gray-900 mb-2">
                                {{ __('common.Resource Type') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="resourceable_type" id="resourceable_type" required x-model="resourceType" @change="resourceId = ''" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
                                <option value="">{{ __('common.Select type...') }}</option>
                                <option value="App\Models\Course">{{ __('common.Course') }}</option>
                                <option value="App\Models\Booking">{{ __('common.Booking') }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="resourceable_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                {{ __('common.Select Item') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="resourceable_id" id="resourceable_id" required x-model="resourceId" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition">
                                <option value="">{{ __('common.Select item...') }}</option>
                                <template x-if="items.length === 0 && resourceType">
                                    <option value="" disabled x-text="resourceType === 'App\\Models\\Booking' ? '{{ __('common.No bookings available') }}' : '{{ __('common.No courses available') }}'"></option>
                                </template>
                                <template x-for="item in items" :key="item.id">
                                    <option :value="item.id" x-text="getItemLabel(item)"></option>
                                </template>
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ __('common.Title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition @error('title') border-red-500 @enderror" placeholder="{{ __('common.Resource title') }}">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ __('common.Description') }}
                        </label>
                        <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition resize-none" placeholder="{{ __('common.Brief description of the resource...') }}">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="block text-sm font-semibold text-gray-900 mb-2">
                            {{ __('common.File') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="file" id="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">{{ __('common.Max 50MB') }}</p>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ __('common.Make this resource public to all students') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('teacher.resources.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            {{ __('common.Cancel') }}
                        </a>
                        <button type="submit" :disabled="submitting" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">{{ __('common.Upload Resource') }}</span>
                            <span x-show="submitting" x-cloak class="inline-flex items-center">
                                <svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('common.Uploading...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
