<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $user->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- User Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    {{ $user->email }}
                                </p>
                                @php
                                    $role = $user->roles->first();
                                    $roleName = $role ? $role->name : 'N/A';
                                    $roleColors = [
                                        'admin' => 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300',
                                        'teacher' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300',
                                        'student' => 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300',
                                    ];
                                    $colorClass = $roleColors[$roleName] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
                                @endphp
                                <p class="mt-2">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $colorClass }}">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Member Since') }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <!-- Role Management -->
                    @if(!$user->isAdmin())
                        <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl border-2 border-indigo-200 dark:border-indigo-700/50">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.Manage Role') }}</h3>
                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('common.Change Role') }}
                                        </label>
                                        <select name="role" id="role" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="student" {{ $user->isStudent() ? 'selected' : '' }}>{{ __('common.Student') }}</option>
                                            <option value="teacher" {{ $user->isTeacher() ? 'selected' : '' }}>{{ __('common.Teacher') }}</option>
                                        </select>
                                    </div>
                                    <div class="flex items-end">
                                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition">
                                            {{ __('common.Update Role') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                @if($user->isStudent())
                                    {{ __('common.Promote this user to teacher to allow them to create courses and manage bookings.') }}
                                @elseif($user->isTeacher())
                                    {{ __('common.Demote this user to student to remove teacher privileges.') }}
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('common.Admin roles cannot be changed.') }}
                            </p>
                        </div>
                    @endif

                    <!-- Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total Bookings') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_bookings'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Completed') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['completed_bookings'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Courses') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_courses'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total Spent') }}</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_spent'], 2) }} BHD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            @if($user->bookings->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Recent Bookings') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($user->bookings->take(10) as $booking)
                                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent rounded-xl border border-blue-100 dark:border-blue-800/50">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $booking->subject->name }}</h4>
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                {{ $booking->status->label() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $booking->start_at->format('M j, Y g:i A') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('common.with') }} <span class="font-semibold">{{ $booking->teacher->user->name }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Enrolled Courses -->
            @if($user->courseEnrollments->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Enrolled Courses') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->courseEnrollments as $enrollment)
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-transparent dark:from-purple-900/20 dark:to-transparent rounded-xl border border-purple-100 dark:border-purple-800/50">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $enrollment->course->title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $enrollment->course->subject->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ __('common.Enrolled on') }} {{ $enrollment->created_at->format('M j, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
