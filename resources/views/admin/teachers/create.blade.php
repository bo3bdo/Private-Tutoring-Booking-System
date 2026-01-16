<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.teachers.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add New Teacher
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Teacher Information</h3>
                        <p class="text-sm text-gray-600">Create a new teacher account</p>
                    </div>

                    <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Basic Information</h4>
                            
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('name') border-red-500 @enderror"
                                    placeholder="John Teacher">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="w-full pl-12 rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('email') border-red-500 @enderror"
                                        placeholder="teacher@example.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" id="password" required
                                        class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('password') border-red-500 @enderror"
                                        placeholder="••••••••">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition">
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Profile -->
                        <div class="space-y-4 pt-6 border-t border-slate-200">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Teacher Profile</h4>
                            
                            <div>
                                <label for="bio" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Bio <span class="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <textarea name="bio" id="bio" rows="3"
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition resize-none @error('bio') border-red-500 @enderror"
                                    placeholder="Brief description about the teacher...">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hourly_rate" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Hourly Rate (BHD) <span class="text-gray-400 font-normal">(Optional)</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 text-sm">BHD</span>
                                        </div>
                                        <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate') }}" step="0.01" min="0"
                                            class="w-full pl-16 rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('hourly_rate') border-red-500 @enderror"
                                            placeholder="25.00">
                                    </div>
                                    @error('hourly_rate')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="default_location_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Default Location <span class="text-gray-400 font-normal">(Optional)</span>
                                    </label>
                                    <select name="default_location_id" id="default_location_id"
                                        class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('default_location_id') border-red-500 @enderror">
                                        <option value="">Select a location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('default_location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('default_location_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="default_meeting_provider" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Default Meeting Provider <span class="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <select name="default_meeting_provider" id="default_meeting_provider"
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition @error('default_meeting_provider') border-red-500 @enderror">
                                    <option value="none" {{ old('default_meeting_provider', 'none') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="custom" {{ old('default_meeting_provider') == 'custom' ? 'selected' : '' }}>Custom URL</option>
                                    <option value="zoom" {{ old('default_meeting_provider') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                    <option value="google_meet" {{ old('default_meeting_provider') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                </select>
                                @error('default_meeting_provider')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Teaching Options -->
                        <div class="space-y-4 pt-6 border-t border-slate-200">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Teaching Options</h4>
                            
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_online" value="1" {{ old('supports_online') ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Supports Online Lessons</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="supports_in_person" value="1" {{ old('supports_in_person') ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Supports In-Person Lessons</span>
                                </label>

                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Active</span>
                                </label>
                            </div>
                        </div>

                        <!-- Subjects -->
                        <div class="pt-6 border-t border-slate-200">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Subjects</h4>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                @forelse($subjects as $subject)
                                    <label class="flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-3 text-sm font-medium text-gray-900">{{ $subject->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-gray-500 text-sm">No active subjects available. <a href="{{ route('admin.subjects.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a></p>
                                @endforelse
                            </div>
                            @error('subjects.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
