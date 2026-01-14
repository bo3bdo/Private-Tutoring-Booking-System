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
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('teacher.subjects.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg border-2 border-blue-200 p-6 hover:shadow-xl hover:border-blue-300 transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-700 transition">My Subjects</h3>
                            <p class="text-xs text-gray-600 mt-1">Select subjects you teach</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-600 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('teacher.slots.index') }}" class="group bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl shadow-lg border-2 border-emerald-200 p-6 hover:shadow-xl hover:border-emerald-300 transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-700 transition">Manage Slots</h3>
                            <p class="text-xs text-gray-600 mt-1">View and manage time slots</p>
                        </div>
                        <svg class="w-5 h-5 text-emerald-600 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('teacher.availability.index') }}" class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg border-2 border-purple-200 p-6 hover:shadow-xl hover:border-purple-300 transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-purple-700 transition">Availability</h3>
                            <p class="text-xs text-gray-600 mt-1">Set weekly schedule</p>
                        </div>
                        <svg class="w-5 h-5 text-purple-600 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('teacher.bookings.index') }}" class="group bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl shadow-lg border-2 border-amber-200 p-6 hover:shadow-xl hover:border-amber-300 transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-amber-700 transition">Bookings</h3>
                            <p class="text-xs text-gray-600 mt-1">View all bookings</p>
                        </div>
                        <svg class="w-5 h-5 text-amber-600 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
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
