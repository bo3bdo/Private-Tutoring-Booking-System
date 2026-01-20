<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('common.Become a Teacher') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('common.Join as a Teacher') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Fill out the form below to request becoming a teacher. Our team will review your application and get back to you soon.') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('student.teacher-request.store') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6 space-y-6">
                @csrf

                <!-- Qualifications -->
                <div>
                    <label for="qualifications" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Qualifications') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="qualifications" id="qualifications" rows="4" required class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition resize-none @error('qualifications') border-red-500 @enderror" placeholder="{{ __('common.Please list your educational qualifications, certifications, degrees, etc.') }}">{{ old('qualifications') }}</textarea>
                    @error('qualifications')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experience -->
                <div>
                    <label for="experience" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Experience') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="experience" id="experience" rows="4" required class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition resize-none @error('experience') border-red-500 @enderror" placeholder="{{ __('common.Please describe your teaching experience, years of experience, subjects taught, etc.') }}">{{ old('experience') }}</textarea>
                    @error('experience')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Bio') }}
                    </label>
                    <textarea name="bio" id="bio" rows="3" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition resize-none @error('bio') border-red-500 @enderror" placeholder="{{ __('common.A brief introduction about yourself...') }}">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subjects -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Subjects') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($subjects as $subject)
                            <label class="flex items-center gap-2 p-3 rounded-xl border-2 border-slate-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 cursor-pointer transition">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('subjects')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hourly Rate -->
                <div>
                    <label for="hourly_rate" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Hourly Rate (BHD)') }}
                    </label>
                    <input type="number" name="hourly_rate" id="hourly_rate" step="0.01" min="0" max="9999.99" value="{{ old('hourly_rate') }}" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition @error('hourly_rate') border-red-500 @enderror" placeholder="0.00">
                    @error('hourly_rate')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teaching Options -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('common.Teaching Options') }}
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-slate-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 cursor-pointer transition">
                            <input type="checkbox" name="supports_online" value="1" {{ old('supports_online') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('common.Online Teaching') }}</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('common.I can teach online via video calls') }}</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-slate-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 cursor-pointer transition">
                            <input type="checkbox" name="supports_in_person" value="1" {{ old('supports_in_person') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:bg-gray-700">
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('common.In-Person Teaching') }}</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('common.I can teach in person at a location') }}</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Location (if in-person) -->
                <div id="location-field" style="display: none;">
                    <label for="default_location_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Default Location') }}
                    </label>
                    <select name="default_location_id" id="default_location_id" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <option value="">{{ __('common.Select a location...') }}</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('default_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Meeting Provider (if online) -->
                <div id="meeting-provider-field" style="display: none;">
                    <label for="default_meeting_provider" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('common.Meeting Provider') }}
                    </label>
                    <select name="default_meeting_provider" id="default_meeting_provider" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:border-blue-400 dark:focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <option value="none" {{ old('default_meeting_provider', 'none') === 'none' ? 'selected' : '' }}>{{ __('common.None') }}</option>
                        <option value="zoom" {{ old('default_meeting_provider') === 'zoom' ? 'selected' : '' }}>Zoom</option>
                        <option value="google_meet" {{ old('default_meeting_provider') === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                        <option value="custom" {{ old('default_meeting_provider') === 'custom' ? 'selected' : '' }}>{{ __('common.Custom') }}</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-gray-700">
                    <a href="{{ route('student.dashboard') }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                        {{ __('common.Cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-purple-700 hover:to-indigo-700 transition">
                        {{ __('common.Submit Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const onlineCheckbox = document.querySelector('input[name="supports_online"]');
            const inPersonCheckbox = document.querySelector('input[name="supports_in_person"]');
            const locationField = document.getElementById('location-field');
            const meetingProviderField = document.getElementById('meeting-provider-field');

            function toggleFields() {
                if (inPersonCheckbox.checked) {
                    locationField.style.display = 'block';
                } else {
                    locationField.style.display = 'none';
                }

                if (onlineCheckbox.checked) {
                    meetingProviderField.style.display = 'block';
                } else {
                    meetingProviderField.style.display = 'none';
                }
            }

            onlineCheckbox.addEventListener('change', toggleFields);
            inPersonCheckbox.addEventListener('change', toggleFields);
            toggleFields(); // Initial check
        });
    </script>
</x-app-layout>
