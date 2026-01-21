<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 dark:from-purple-800 dark:via-indigo-900 dark:to-indigo-950 p-4 sm:p-6 lg:p-8 mb-4 sm:mb-6 lg:mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-900/90 via-indigo-900/90 to-purple-900/90 dark:from-indigo-950/95 dark:via-indigo-950/95 dark:to-indigo-950/95 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat opacity-30 dark:opacity-20"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Create Discount') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-purple-100 dark:text-purple-200 mt-1 sm:mt-2 drop-shadow-md">
                        {{ __('common.Create a new discount code for your platform') }}
                    </p>
                </div>
                <div class="hidden md:block flex-shrink-0 ml-4">
                    <div class="w-16 h-16 lg:w-24 lg:h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-xl lg:rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8 -mt-4 sm:-mt-6 lg:-mt-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-4 sm:space-y-6">
                        @csrf
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Code') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('code') border-red-500 @enderror">
                            @error('code')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Type') }} <span class="text-red-500">*</span></label>
                            <select name="type" id="type" required
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                                <option value="percentage">{{ __('common.Percentage') }}</option>
                                <option value="fixed">{{ __('common.Fixed') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="value" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Value') }} <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="value" id="value" value="{{ old('value') }}" required
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('value') border-red-500 @enderror">
                            @error('value')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="max_discount_amount" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Max Discount Amount') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="number" step="0.01" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="min_amount" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Minimum Amount') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="number" step="0.01" name="min_amount" id="min_amount" value="{{ old('min_amount') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="max_uses" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Max Uses') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="max_uses_per_user" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Max Uses Per User') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="number" name="max_uses_per_user" id="max_uses_per_user" value="{{ old('max_uses_per_user') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="starts_at" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Starts At') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label for="expires_at" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">{{ __('common.Expires At') }} <span class="text-gray-500 dark:text-gray-400 text-xs">({{ __('common.Optional') }})</span></label>
                            <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked
                                    class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-purple-600 text-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600">
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('common.Is Active') }}</span>
                            </label>
                        </div>
                        <div class="flex gap-2 sm:gap-3 pt-4">
                            <button type="submit" class="flex-1 sm:flex-none bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-600 text-white px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition shadow-lg hover:shadow-xl">
                                {{ __('common.Create') }}
                            </button>
                            <a href="{{ route('admin.discounts.index') }}" class="flex-1 sm:flex-none bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition shadow-lg hover:shadow-xl text-center">
                                {{ __('common.Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
