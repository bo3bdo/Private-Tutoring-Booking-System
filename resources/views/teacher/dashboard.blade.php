<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    Teacher Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your teaching schedule and bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Today Earnings -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl shadow-lg border-2 border-emerald-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wide mb-1">Today's Earnings</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($todayEarnings, 2) }} BHD</p>
                </div>

                <!-- Week Earnings -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border-2 border-blue-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-1">This Week</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($weekEarnings, 2) }} BHD</p>
                </div>

                <!-- Month Earnings -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg border-2 border-purple-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide mb-1">This Month</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($monthEarnings, 2) }} BHD</p>
                </div>

                <!-- Total Earnings -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl shadow-lg border-2 border-amber-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-amber-700 uppercase tracking-wide mb-1">Total Earnings</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalEarnings, 2) }} BHD</p>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $completedBookings }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Upcoming</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $upcomingBookingsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Active Students</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $uniqueStudents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance & Additional Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Attendance Rate -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Attendance Rate</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $attendanceRate }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Average Booking Value -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Avg Booking Value</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($averageBookingValue, 2) }} BHD</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Pending Payments</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($pendingPaymentsAmount, 2) }} BHD</p>
                        </div>
                    </div>
                </div>

                <!-- Total Teaching Hours -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Teaching Hours</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalHours, 1) }}h</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth & Subjects Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Monthly Growth -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Monthly Growth</p>
                            <p class="text-2xl font-bold {{ $monthGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                                {{ $monthGrowth >= 0 ? '+' : '' }}{{ $monthGrowth }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- New Bookings This Month -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">New This Month</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $newBookingsThisMonth }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subjects Count -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Subjects</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $subjectsCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Most Booked Subject -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Top Subject</p>
                            <p class="text-lg font-bold text-gray-900 mt-1 truncate">{{ $mostBookedSubjectName }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Upcoming Bookings -->
            @if($upcomingBookings->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Upcoming Bookings</h3>
                                    <p class="text-sm text-gray-600">Your next scheduled lessons</p>
                                </div>
                            </div>
                            <a href="{{ route('teacher.bookings.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
                                <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-transparent rounded-xl border border-blue-100 hover:border-blue-200 transition">
                                    <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-semibold text-gray-900">{{ $booking->subject->name }}</h4>
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800">
                                                Confirmed
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-1">{{ $booking->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                                        <p class="text-sm text-gray-600">Student: <span class="font-semibold">{{ $booking->student->name }}</span></p>
                                    </div>
                                    <a href="{{ route('teacher.bookings.show', $booking) }}" class="flex-shrink-0 text-blue-600 hover:text-blue-700">
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
