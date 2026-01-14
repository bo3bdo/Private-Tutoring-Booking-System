<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'upcoming']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'upcoming' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                            Upcoming
                        </a>
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'past']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'past' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                            Past
                        </a>
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'confirmed']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'confirmed' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                            Confirmed
                        </a>
                        <a href="{{ route('teacher.bookings.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ !request('filter') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                            All
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($bookings as $booking)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->subject->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">Student: {{ $booking->student->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mt-2
                                        @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status->value === 'awaiting_payment') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ $booking->status->label() }}
                                    </span>
                                </div>
                                <a href="{{ route('teacher.bookings.show', $booking) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <p class="text-gray-500">No bookings found.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
