<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 dark:from-purple-800 dark:via-indigo-800 dark:to-blue-800 p-8 mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-900/80 via-indigo-900/80 to-blue-900/80 dark:from-purple-950/90 dark:via-indigo-950/90 dark:to-blue-950/90 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80')] bg-cover bg-center bg-no-repeat opacity-50 dark:opacity-30"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Teacher Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('common.Manage your teaching schedule and bookings') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 -mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Today Earnings -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-2xl shadow-lg border-2 border-emerald-200 dark:border-emerald-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 uppercase tracking-wide mb-1">{{ __("common.Today's Earnings") }}</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayEarnings, 2) }} BHD</p>
                </div>

                <!-- Week Earnings -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl shadow-lg border-2 border-blue-200 dark:border-blue-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide mb-1">{{ __('common.This Week') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($weekEarnings, 2) }} BHD</p>
                </div>

                <!-- Month Earnings -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl shadow-lg border-2 border-purple-200 dark:border-purple-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide mb-1">{{ __('common.This Month') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($monthEarnings, 2) }} BHD</p>
                </div>

                <!-- Total Earnings -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/30 rounded-2xl shadow-lg border-2 border-amber-200 dark:border-amber-700/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wide mb-1">{{ __('common.Total Earnings') }}</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEarnings, 2) }} BHD</p>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Total Bookings') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Completed') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Upcoming') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $upcomingBookingsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Active Students') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $uniqueStudents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance & Additional Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Attendance Rate -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Attendance Rate') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $attendanceRate }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Avg Booking Value') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($averageBookingValue, 2) }} BHD</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Pending Payments') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($pendingPaymentsAmount, 2) }} BHD</p>
                        </div>
                    </div>
                </div>

                <!-- Total Teaching Hours -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Teaching Hours') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalHours, 1) }}h</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth & Subjects Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Monthly Growth -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Monthly Growth') }}</p>
                            <p class="text-2xl font-bold {{ $monthGrowth >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                                {{ $monthGrowth >= 0 ? '+' : '' }}{{ $monthGrowth }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Bookings This Month -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.New This Month') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $newBookingsThisMonth }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subjects Count -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Subjects') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $subjectsCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Most Booked Subject -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Top Subject') }}</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white mt-1 truncate">{{ $mostBookedSubjectName }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Upcoming Bookings -->
            @if($upcomingBookings->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Upcoming Bookings') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Your next scheduled lessons') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('teacher.bookings.index') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                {{ __('common.View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
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
                                                {{ __('common.Confirmed') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $booking->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Student') }}: <span class="font-semibold">{{ $booking->student->name }}</span></p>
                                    </div>
                                    <a href="{{ route('teacher.bookings.show', $booking) }}" class="flex-shrink-0 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
