<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <a href="{{ route('teacher.subjects.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">My Subjects</h3>
                    <p class="text-sm text-gray-600 mt-2">Select subjects you can teach</p>
                </a>
                <a href="{{ route('teacher.slots.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Manage Slots</h3>
                    <p class="text-sm text-gray-600 mt-2">View and manage your time slots</p>
                </a>
                <a href="{{ route('teacher.availability.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Availability</h3>
                    <p class="text-sm text-gray-600 mt-2">Set your weekly availability</p>
                </a>
                <a href="{{ route('teacher.bookings.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900">Bookings</h3>
                    <p class="text-sm text-gray-600 mt-2">View all your bookings</p>
                </a>
            </div>

            @if($upcomingBookings->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Bookings</h3>
                        <div class="space-y-3">
                            @foreach($upcomingBookings as $booking)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <p class="font-semibold text-gray-900">{{ $booking->subject->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->start_at->format('M j, Y g:i A') }}</p>
                                    <p class="text-sm text-gray-600">Student: {{ $booking->student->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
