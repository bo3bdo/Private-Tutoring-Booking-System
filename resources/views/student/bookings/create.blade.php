<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.Create Booking') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Booking Summary Card -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('common.Booking Summary') }}</h3>
                            <p class="text-sm text-gray-500">{{ __('common.Review your booking details') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Teacher') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $teacher->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Subject') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $subject->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Date & Time') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $slot->start_at->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $slot->start_at->format('g:i A') }} - {{ $slot->end_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Duration') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $slot->start_at->diffInMinutes($slot->end_at) }} {{ __('common.minutes') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">{{ __('common.Please fix the following errors:') }}</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('common.Booking Preferences') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('common.Select your preferred lesson mode and provide any additional notes') }}</p>
                    </div>

                    <form method="POST" action="{{ route('student.bookings.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <!-- Lesson Mode -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ __('common.Lesson Mode') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @if($teacher->supports_online)
                                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-300 hover:bg-blue-50 {{ old('lesson_mode') === 'online' ? 'border-blue-500 bg-blue-50' : 'border-slate-200' }}">
                                        <input type="radio" name="lesson_mode" value="online" {{ old('lesson_mode') === 'online' ? 'checked' : '' }} class="sr-only" required>
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ __('common.Online') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('common.Video call lesson') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 {{ old('lesson_mode') === 'online' ? 'border-blue-500 bg-blue-500' : 'border-slate-300' }}">
                                                @if(old('lesson_mode') === 'online')
                                                    <div class="w-full h-full rounded-full bg-blue-500 flex items-center justify-center">
                                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endif

                                @if($teacher->supports_in_person)
                                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-emerald-300 hover:bg-emerald-50 {{ old('lesson_mode') === 'in_person' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200' }}">
                                        <input type="radio" name="lesson_mode" value="in_person" {{ old('lesson_mode') === 'in_person' ? 'checked' : '' }} class="sr-only" required>
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ __('common.In Person') }}</p>
                                                <p class="text-xs text-gray-500">{{ __('common.Physical location') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 rounded-full border-2 {{ old('lesson_mode') === 'in_person' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }}">
                                                @if(old('lesson_mode') === 'in_person')
                                                    <div class="w-full h-full rounded-full bg-emerald-500 flex items-center justify-center">
                                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            </div>
                            @error('lesson_mode')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Online Fields -->
                        <div id="online_fields" class="hidden">
                            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900 mb-1">{{ __('common.Meeting Link Information') }}</p>
                                        <p class="text-sm text-blue-800">
                                            {{ __('common.The meeting URL will be provided by your teacher after booking confirmation.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- In Person Fields -->
                        <div id="in_person_fields" class="hidden">
                            <div class="bg-emerald-50 border-l-4 border-emerald-400 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-900 mb-1">{{ __('common.Location Information') }}</p>
                                        <p class="text-sm text-emerald-800">
                                            {{ __('common.The meeting location will be determined by your teacher after booking confirmation.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                {{ __('common.Additional Notes') }} <span class="text-gray-400 font-normal">{{ __('common.(Optional)') }}</span>
                            </label>
                            <textarea name="notes" rows="4" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none" placeholder="{{ __('common.Any special requests, learning goals, or notes for your teacher...') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                                {{ __('common.Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transform hover:scale-105 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('common.Confirm Booking') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lessonModeInputs = document.querySelectorAll('input[name="lesson_mode"]');
            const onlineFields = document.getElementById('online_fields');
            const inPersonFields = document.getElementById('in_person_fields');

            function toggleFields() {
                const selectedMode = document.querySelector('input[name="lesson_mode"]:checked')?.value;
                
                onlineFields.classList.add('hidden');
                inPersonFields.classList.add('hidden');
                
                if (selectedMode === 'online') {
                    onlineFields.classList.remove('hidden');
                } else if (selectedMode === 'in_person') {
                    inPersonFields.classList.remove('hidden');
                }
            }

            lessonModeInputs.forEach(input => {
                input.addEventListener('change', toggleFields);
            });

            // Initialize on page load
            toggleFields();
        });
    </script>
</x-app-layout>
