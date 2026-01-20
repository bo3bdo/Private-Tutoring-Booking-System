<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <a href="{{ route('admin.users.index') }}" class="flex-shrink-0 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition p-1 -ml-1">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="min-w-0 flex-1">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-white leading-tight truncate">
                        {{ __('common.Edit User') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 truncate mt-0.5">{{ $user->name }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6 md:p-8">
                    <div class="mb-4 sm:mb-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-1 sm:mb-2">{{ __('common.User Information') }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('common.Update user account details and role') }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 sm:space-y-6" x-data="{ role: '{{ old('role', $currentRole) }}' }">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="space-y-3 sm:space-y-4">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ __('common.Basic Information') }}</h4>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Full Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('name') border-red-500 @enderror"
                                    placeholder="{{ __('common.John Doe') }}">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Email Address') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full pl-10 sm:pl-12 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('email') border-red-500 @enderror"
                                        placeholder="user@example.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Role') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" x-model="role" required
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('role') border-red-500 @enderror">
                                    <option value="student" {{ old('role', $currentRole) === 'student' ? 'selected' : '' }}>{{ __('common.Student') }}</option>
                                    <option value="teacher" {{ old('role', $currentRole) === 'teacher' ? 'selected' : '' }}>{{ __('common.Teacher') }}</option>
                                </select>
                                @error('role')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                        {{ __('common.New Password') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Leave blank to keep current') }})</span>
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('password') border-red-500 @enderror"
                                        placeholder="••••••••">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                        {{ __('common.Confirm New Password') }}
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Profile (shown only when role is teacher) -->
                        <div x-show="role === 'teacher'" x-transition class="space-y-3 sm:space-y-4 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ __('common.Teacher Profile') }}</h4>
                            
                            <div>
                                <label for="bio" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Bio') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                </label>
                                <textarea name="bio" id="bio" rows="3"
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition resize-none @error('bio') border-red-500 @enderror"
                                    placeholder="{{ __('common.Brief description about the teacher...') }}">{{ old('bio', $user->teacherProfile?->bio ?? '') }}</textarea>
                                @error('bio')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label for="hourly_rate" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                        {{ __('common.Hourly Rate') }} (BHD) <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">BHD</span>
                                        </div>
                                        <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $user->teacherProfile?->hourly_rate ?? 0) }}" step="0.01" min="0"
                                            class="w-full pl-12 sm:pl-16 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('hourly_rate') border-red-500 @enderror"
                                            placeholder="25.00">
                                    </div>
                                    @error('hourly_rate')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="default_location_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                        {{ __('common.Default Location') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                    </label>
                                    <select name="default_location_id" id="default_location_id"
                                        class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('default_location_id') border-red-500 @enderror">
                                        <option value="">{{ __('common.Select a location') }}</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('default_location_id', $user->teacherProfile?->default_location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('default_location_id')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="default_meeting_provider" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Default Meeting Provider') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                </label>
                                <select name="default_meeting_provider" id="default_meeting_provider"
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('default_meeting_provider') border-red-500 @enderror">
                                    <option value="none" {{ old('default_meeting_provider', $user->teacherProfile?->default_meeting_provider?->value ?? 'none') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="custom" {{ old('default_meeting_provider', $user->teacherProfile?->default_meeting_provider?->value ?? 'none') == 'custom' ? 'selected' : '' }}>Custom URL</option>
                                    <option value="zoom" {{ old('default_meeting_provider', $user->teacherProfile?->default_meeting_provider?->value ?? 'none') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="google_meet" {{ old('default_meeting_provider', $user->teacherProfile?->default_meeting_provider?->value ?? 'none') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                </select>
                                @error('default_meeting_provider')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Teaching Options -->
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_online" value="1" {{ old('supports_online', $user->teacherProfile?->supports_online ?? false) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Supports Online Lessons') }}</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_in_person" value="1" {{ old('supports_in_person', $user->teacherProfile?->supports_in_person ?? false) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Supports In-Person Lessons') }}</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->teacherProfile?->is_active ?? true) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">{{ __('common.Active') }}</span>
                                </label>
                            </div>

                            <!-- Subjects -->
                            <div>
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4">{{ __('common.Subjects') }}</h5>
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    @forelse($subjects as $subject)
                                        <label class="flex items-center p-3 border border-slate-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                                   {{ in_array($subject->id, old('subjects', $teacherSubjectIds)) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $subject->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('common.No active subjects available.') }} <a href="{{ route('admin.subjects.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">{{ __('common.Create one') }}</a></p>
                                    @endforelse
                                </div>
                                @error('subjects.*')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Student Profile (shown only when role is student) -->
                        <div x-show="role === 'student'" x-transition class="space-y-3 sm:space-y-4 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ __('common.Student Profile') }}</h4>
                            
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5 sm:mb-2">
                                    {{ __('common.Phone Number') }} <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">({{ __('common.Optional') }})</span>
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->studentProfile?->phone ?? '') }}"
                                    class="w-full rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition @error('phone') border-red-500 @enderror"
                                    placeholder="+97312345678">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 sm:pt-6 border-t border-slate-200 dark:border-gray-700">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-slate-300 dark:border-gray-600 rounded-lg sm:rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-slate-50 dark:hover:bg-gray-600 hover:border-slate-400 dark:hover:border-gray-500 transition">
                                {{ __('common.Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-lg sm:rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('common.Update User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
