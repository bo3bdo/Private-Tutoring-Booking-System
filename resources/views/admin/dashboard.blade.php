<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.subjects.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50 transition">
                    <p class="text-sm text-gray-600">Manage Subjects</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">View All →</p>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Pending Payments</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['pending_payments'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Active Teachers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_teachers'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Active Students</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_students'] }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.subjects.index') }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50 transition">
                            <p class="font-semibold text-gray-900">Manage Subjects</p>
                            <p class="text-sm text-gray-600 mt-1">Add, edit, or delete subjects</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
                    <div class="space-y-3">
                        @forelse($recentBookings as $booking)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <p class="font-semibold text-gray-900">{{ $booking->subject->name }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->student->name }} with {{ $booking->teacher->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->start_at->format('M j, Y g:i A') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No recent bookings.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
