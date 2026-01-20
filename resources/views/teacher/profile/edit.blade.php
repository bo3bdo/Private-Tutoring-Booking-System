<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl lg:text-2xl text-gray-800 dark:text-white leading-tight">
                    {{ __('common.Teacher Profile') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Update your teaching profile and settings') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-1 sm:mb-2">{{ __('common.Profile Information') }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('common.Manage your teaching profile, hourly rate, and preferences') }}</p>
                    </div>

                    <form method="POST" action="{{ route('teacher.profile.update') }}" class="space-y-4 sm:space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="space-y-3 sm:space-y-4 pb-4 sm:pb-6 border-b border-slate-200 dark:border-gray-700">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ __('common.Basic Information') }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('common.To update your name or email, please visit') }} <a href="{{ route('profile.edit') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ __('common.Account Settings') }}</a></p>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 sm:p-4 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('common.Name') }}:</span>
                                    <span class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('common.Email') }}:</span>
                                    <span class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                {{ __('common.Bio') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                            </label>
                            <textarea name="bio" id="bio" rows="4"
                                class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition resize-none @error('bio') border-red-500 @enderror"
                                placeholder="{{ __('common.Brief description about the teacher...') }}">{{ old('bio', $teacher->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                {{ __('common.Hourly Rate') }} (BHD) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">BHD</span>
                                </div>
                                <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $teacher->hourly_rate ?? 0) }}" step="0.01" min="0" required
                                    class="w-full pl-12 sm:pl-16 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('hourly_rate') border-red-500 @enderror"
                                    placeholder="25.00">
                            </div>
                            @error('hourly_rate')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">{{ __('common.This is the rate students will pay per hour for your lessons') }}</p>
                        </div>

                        <!-- Location and Meeting Provider -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label for="default_location_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Default Location') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                </label>
                                <select name="default_location_id" id="default_location_id"
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('default_location_id') border-red-500 @enderror">
                                    <option value="">{{ __('common.Select a location') }}</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('default_location_id', $teacher->default_location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_location_id')
                                    <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="default_meeting_provider" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Default Meeting Provider') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                </label>
                                <select name="default_meeting_provider" id="default_meeting_provider"
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('default_meeting_provider') border-red-500 @enderror">
                                    @foreach($meetingProviders as $provider)
                                        <option value="{{ $provider->value }}" {{ old('default_meeting_provider', $teacher->default_meeting_provider->value) == $provider->value ? 'selected' : '' }}>
                                            {{ $provider->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_meeting_provider')
                                    <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Teaching Options -->
                        <div class="space-y-3 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ __('common.Teaching Options') }}</h4>
                            
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_online" value="1" {{ old('supports_online', $teacher->supports_online) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Supports Online Lessons') }}</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_in_person" value="1" {{ old('supports_in_person', $teacher->supports_in_person) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Supports In-Person Lessons') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Subjects -->
                        <div class="pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2 sm:mb-3">{{ __('common.Subjects') }} <span class="text-red-500">*</span></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('common.Select at least one subject you teach') }}</p>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @forelse($subjects as $subject)
                                    <label class="flex items-center p-3 border border-slate-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors">
                                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subjects', $teacherSubjectIds)) ? 'checked' : '' }}
                                               class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $subject->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('common.No active subjects available.') }}</p>
                                @endforelse
                            </div>
                            @error('subjects.*')
                                <p class="mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('subjects')
                                <p class="mt-2 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <button type="submit" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-lg sm:rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('common.Update Profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
