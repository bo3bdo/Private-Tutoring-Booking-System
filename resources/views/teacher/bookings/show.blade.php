<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('teacher.bookings.index') }}" class="text-blue-600 hover:text-blue-800">Bookings</a> / Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $booking->subject->name }}</h1>
                            <p class="text-gray-600 mt-1">Student: {{ $booking->student->name }}</p>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Meeting URL</h3>
                            @if($booking->meeting_url)
                                <div class="mb-3 p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm font-semibold text-blue-900 mb-1">Current Meeting Link:</p>
                                    <a href="{{ $booking->meeting_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline break-all">
                                        {{ $booking->meeting_url }}
                                    </a>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('teacher.bookings.update-meeting-url', $booking) }}" class="flex gap-3">
                                @csrf
                                @method('PATCH')
                                <input type="url" name="meeting_url" value="{{ old('meeting_url', $booking->meeting_url) }}" 
                                    class="flex-1 rounded-lg border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2" 
                                    placeholder="https://meet.google.com/... or https://zoom.us/j/...">
                                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                    {{ $booking->meeting_url ? 'Update' : 'Set' }} URL
                                </button>
                            </form>
                            @error('meeting_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if($booking->isConfirmed())
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('teacher.bookings.status', $booking) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                        Mark Completed
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('teacher.bookings.status', $booking) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="no_show">
                                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                                        Mark No Show
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
