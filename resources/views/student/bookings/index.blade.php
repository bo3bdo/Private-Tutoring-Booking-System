<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    My Bookings
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage and view all your lesson bookings</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <a href="{{ route('student.bookings.index') }}" 
                           class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('filter') ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            All
                        </a>
                        <a href="{{ route('student.bookings.index', ['filter' => 'upcoming']) }}" 
                           class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('filter') === 'upcoming' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Upcoming
                        </a>
                        <a href="{{ route('student.bookings.index', ['filter' => 'past']) }}" 
                           class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('filter') === 'past' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Past
                        </a>
                        <a href="{{ route('student.bookings.index', ['filter' => 'cancelled']) }}" 
                           class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('filter') === 'cancelled' ? 'bg-red-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Cancelled
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="space-y-4">
                @forelse($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-200">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-lg font-bold text-gray-900">{{ $booking->subject->name }}</h3>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                    @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status->value === 'awaiting_payment') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-800
                                                    @elseif($booking->status->value === 'completed') bg-blue-100 text-blue-800
                                                    @else bg-slate-100 text-slate-800
                                                    @endif">
                                                    {{ $booking->status->label() }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span>{{ $booking->teacher->user->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $booking->start_at->format('M j, Y g:i A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('student.bookings.show', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition">
                                        View Details
                                    </a>
                                    @if($booking->isAwaitingPayment())
                                        <a href="{{ route('student.bookings.pay', $booking) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-md hover:from-emerald-700 hover:to-emerald-800 transform hover:scale-105 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Pay Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bookings Found</h3>
                        <p class="text-gray-600 mb-6">You don't have any bookings yet.</p>
                        <a href="{{ route('student.subjects.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 transition">
                            Browse Subjects
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
