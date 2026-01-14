<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('student.bookings.index') }}" class="text-blue-600 hover:text-blue-800">My Bookings</a> / Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $booking->subject->name }}</h1>
                            <p class="text-gray-600 mt-1">Teacher: {{ $booking->teacher->user->name }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                            @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status->value === 'awaiting_payment') bg-yellow-100 text-yellow-800
                            @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-800
                            @else bg-slate-100 text-slate-800
                            @endif">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div>
                            <p class="text-sm text-gray-500">Date & Time</p>
                            <p class="font-semibold">{{ $booking->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="font-semibold">{{ $booking->start_at->diffInMinutes($booking->end_at) }} minutes</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Lesson Mode</p>
                            <p class="font-semibold">{{ $booking->lesson_mode->label() }}</p>
                        </div>
                        @if($booking->location)
                            <div>
                                <p class="text-sm text-gray-500">Location</p>
                                <p class="font-semibold">{{ $booking->location->name }}</p>
                            </div>
                        @endif
                    </div>

                    @if($booking->lesson_mode->value === 'online')
                        <div class="mt-6">
                            @if($booking->meeting_url)
                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm font-semibold text-blue-900 mb-2">Meeting Link</p>
                                    <a href="{{ $booking->meeting_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline break-all">
                                        {{ $booking->meeting_url }}
                                    </a>
                                </div>
                            @else
                                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <p class="text-sm text-yellow-800">
                                        <strong>Note:</strong> The meeting URL will be provided by your teacher. Please check back later.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($booking->notes)
                        <div class="mt-6">
                            <p class="text-sm text-gray-500 mb-1">Notes</p>
                            <p class="text-gray-900">{{ $booking->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-6 flex items-center gap-3">
                        @if($booking->isAwaitingPayment())
                            <a href="{{ route('student.bookings.pay', $booking) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Complete Payment
                            </a>
                        @endif
                        @if(!$booking->isCancelled() && !$booking->isCompleted())
                            <form method="POST" action="{{ route('student.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                @csrf
                                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if($booking->histories->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">History</h3>
                        <div class="space-y-3">
                            @foreach($booking->histories as $history)
                                <div class="border-l-2 border-slate-200 pl-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ ucfirst($history->action) }}</p>
                                    <p class="text-xs text-gray-500">{{ $history->created_at->format('M j, Y g:i A') }}</p>
                                    @if($history->actor)
                                        <p class="text-xs text-gray-500">By: {{ $history->actor->name }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
