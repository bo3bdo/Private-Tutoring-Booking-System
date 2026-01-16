<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 dark:from-slate-800 dark:via-slate-900 dark:to-slate-950 p-8 mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-800/90 to-slate-900/90 dark:from-slate-950/95 dark:via-slate-950/95 dark:to-slate-950/95 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80')] bg-cover bg-center bg-no-repeat opacity-50 dark:opacity-30"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Admin Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('common.Overview of your tutoring platform') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 -mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl shadow-lg border-2 border-blue-200 dark:border-blue-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total Bookings') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_bookings'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-2xl shadow-lg border-2 border-yellow-200 dark:border-yellow-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Pending Payments') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_payments'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl shadow-lg border-2 border-emerald-200 dark:border-emerald-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Active Teachers') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_teachers'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl shadow-lg border-2 border-purple-200 dark:border-purple-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Active Students') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_students'] }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Quick Actions') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('admin.subjects.index') }}" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent rounded-xl border-2 border-blue-200 dark:border-blue-700/50 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md transition">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">{{ __('common.Manage Subjects') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Add, edit, or delete subjects') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.locations.index') }}" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent rounded-xl border-2 border-emerald-200 dark:border-emerald-700/50 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition">{{ __('common.Manage Locations') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Add, edit, or delete locations') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <a href="{{ route('admin.teachers.index') }}" class="group flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent rounded-xl border-2 border-indigo-200 dark:border-indigo-700/50 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition">{{ __('common.Manage Teachers') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Add, edit, or delete teachers') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Recent Bookings') }}</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentBookings->isEmpty())
                        <div class="text-center py-8">
                            <div class="flex justify-center mb-4">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('common.No recent bookings.') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent rounded-xl border border-blue-100 dark:border-blue-800/50 hover:border-blue-200 dark:hover:border-blue-700 transition">
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
                                            <span class="font-semibold">{{ $booking->student->name }}</span> {{ __('common.with') }} 
                                            <span class="font-semibold">{{ $booking->teacher->user->name }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
