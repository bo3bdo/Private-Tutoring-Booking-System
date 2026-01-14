<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher.bookings.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Booking Details
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Booking Header Card -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $booking->subject->name }}</h1>
                                <p class="text-gray-600 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $booking->student->name }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold
                            @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status->value === 'awaiting_payment') bg-yellow-100 text-yellow-800
                            @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-800
                            @elseif($booking->status->value === 'completed') bg-blue-100 text-blue-800
                            @else bg-slate-100 text-slate-800
                            @endif">
                            {{ $booking->status->label() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date & Time</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->start_at->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $booking->start_at->format('g:i A') }} - {{ $booking->end_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Duration</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->start_at->diffInMinutes($booking->end_at) }} minutes</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 {{ $booking->lesson_mode->value === 'online' ? 'bg-blue-100' : 'bg-emerald-100' }} rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $booking->lesson_mode->value === 'online' ? 'text-blue-600' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($booking->lesson_mode->value === 'online')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Lesson Mode</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->lesson_mode->label() }}</p>
                            </div>
                        </div>

                        @if($booking->location)
                            <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                                <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $booking->location->name }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Meeting URL (Online) -->
            @if($booking->lesson_mode->value === 'online')
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Meeting URL
                        </h3>
                        @if($booking->meeting_url)
                            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-xl">
                                <p class="text-sm font-semibold text-blue-900 mb-1">Current Meeting Link:</p>
                                <a href="{{ $booking->meeting_url }}" target="_blank" class="flex items-center gap-2 text-blue-700 hover:text-blue-800 font-medium break-all">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    {{ $booking->meeting_url }}
                                </a>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('teacher.bookings.update-meeting-url', $booking) }}" class="flex gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="relative flex-1">
                                <input type="url" name="meeting_url" value="{{ old('meeting_url', $booking->meeting_url) }}" 
                                    class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 pl-12 text-sm font-medium text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition @error('meeting_url') border-red-500 @enderror" 
                                    placeholder="https://meet.google.com/... or https://zoom.us/j/...">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $booking->meeting_url ? 'Update' : 'Set' }} URL
                            </button>
                        </form>
                        @error('meeting_url')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Location (In Person) -->
            @if($booking->lesson_mode->value === 'in_person')
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Meeting Location
                        </h3>
                        @if($booking->location)
                            <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-400 rounded-xl">
                                <p class="text-sm font-semibold text-emerald-900 mb-1">Current Location:</p>
                                <p class="text-emerald-800 font-medium">{{ $booking->location->name }}</p>
                                @if($booking->location->address)
                                    <p class="text-sm text-emerald-700 mt-1 flex items-start gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $booking->location->address }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-900 mb-1">Location Not Set</p>
                                        <p class="text-sm text-yellow-800">Please set the meeting location for this in-person lesson.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('teacher.bookings.update-location', $booking) }}" class="flex gap-3">
                            @csrf
                            @method('PATCH')
                            <div class="relative flex-1">
                                <select name="location_id" class="w-full appearance-none rounded-xl border-2 border-slate-200 bg-white px-4 py-3 pl-12 pr-10 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition @error('location_id') border-red-500 @enderror">
                                    <option value="">Select a location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $booking->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transform hover:scale-105 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $booking->location ? 'Update' : 'Set' }} Location
                            </button>
                        </form>
                        @error('location_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            @endif

            @if($booking->notes)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Student Notes
                        </h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $booking->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Cancellation Reason (if cancelled) -->
            @if($booking->isCancelled() && $booking->cancellation_reason)
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Cancellation Reason
                        </h3>
                        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-xl">
                            <p class="text-sm text-red-900 whitespace-pre-wrap">{{ $booking->cancellation_reason }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($booking->isConfirmed())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <form method="POST" action="{{ route('teacher.bookings.status', $booking) }}">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Mark Completed
                                </button>
                            </form>
                            <form method="POST" action="{{ route('teacher.bookings.status', $booking) }}">
                                @csrf
                                <input type="hidden" name="status" value="no_show">
                                <button type="submit" class="inline-flex items-center px-6 py-3 border-2 border-red-300 bg-red-50 text-red-700 rounded-xl text-sm font-semibold hover:bg-red-100 hover:border-red-400 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Mark No Show
                                </button>
                            </form>
                            <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 border-2 border-red-300 bg-red-50 text-red-700 rounded-xl text-sm font-semibold hover:bg-red-100 hover:border-red-400 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel Booking
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cancel Booking Modal -->
            @if($booking->isConfirmed() || $booking->isAwaitingPayment())
                <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Booking</h3>
                        <p class="text-sm text-gray-600 mb-4">Are you sure you want to cancel this booking? Please provide a reason for cancellation.</p>
                        <form method="POST" action="{{ route('teacher.bookings.cancel', $booking) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="cancellation_reason" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Cancellation Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition resize-none @error('cancellation_reason') border-red-500 @enderror"
                                    placeholder="Please explain why you are cancelling this booking...">{{ old('cancellation_reason') }}</textarea>
                                @error('cancellation_reason')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-red-700 hover:to-red-800 transition">
                                    Confirm Cancellation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
