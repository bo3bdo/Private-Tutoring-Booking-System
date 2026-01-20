<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 min-w-0 flex-1">
            <a href="{{ route('admin.subjects.index') }}" class="flex-shrink-0 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition p-1 -ml-1">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-white leading-tight truncate">
                    {{ __('common.Add New Subject') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4 sm:space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Subject Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Description') }}</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                <span class="ml-2 rtl:mr-2 rtl:ml-0 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Active') }}</span>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-slate-300 dark:border-gray-600 rounded-lg sm:rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-slate-50 dark:hover:bg-gray-600 hover:border-slate-400 dark:hover:border-gray-500 transition">
                                {{ __('common.Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-lg sm:rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transform hover:scale-105 transition">
                                {{ __('common.Create Subject') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
