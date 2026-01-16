<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.teachers.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $teacher->user->name }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 rounded-xl text-sm font-semibold text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Teacher Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $teacher->user->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    {{ $teacher->user->email }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold
                            @if($teacher->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                            @endif">
                            {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($teacher->bio)
                            <div class="md:col-span-2">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Bio</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $teacher->bio }}</p>
                            </div>
                        @endif

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Hourly Rate</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->hourly_rate ? number_format($teacher->hourly_rate, 2).' BHD' : 'Not set' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Default Location</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->defaultLocation?->name ?? 'Not set' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Meeting Provider</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->default_meeting_provider->label() }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Teaching Options</p>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @if($teacher->supports_online)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 text-xs font-semibold text-blue-800 dark:text-blue-300">Online</span>
                                    @endif
                                    @if($teacher->supports_in_person)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 text-xs font-semibold text-emerald-800 dark:text-emerald-300">In Person</span>
                                    @endif
                                    @if(!$teacher->supports_online && !$teacher->supports_in_person)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Not configured</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects -->
            @if($teacher->subjects->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Subjects</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($teacher->subjects as $subject)
                                <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-3 py-1.5 text-sm font-semibold text-blue-800 dark:text-blue-300">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $teacher->bookings()->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Time Slots</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $teacher->timeSlots()->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Availability Rules</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $teacher->availabilities()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
