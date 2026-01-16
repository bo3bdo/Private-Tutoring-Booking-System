<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.bookings.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.Booking Details') }}
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
                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $booking->subject->name }}</h1>
                                <p class="text-gray-600 flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $booking->teacher->user->name }}
                                </p>
                                @php
                                    $teacherRating = $booking->teacher->averageRating();
                                    $teacherReviewsCount = $booking->teacher->reviewsCount();
                                @endphp
                                @if($teacherReviewsCount > 0)
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= round($teacherRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">{{ round($teacherRating) }}</span>
                                        <span class="text-xs text-gray-500">({{ $teacherReviewsCount }} {{ $teacherReviewsCount === 1 ? __('common.review') : __('common.reviews') }})</span>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500">{{ __('common.No reviews yet') }}</p>
                                @endif
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
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Date & Time') }}</p>
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
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Duration') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->start_at->diffInMinutes($booking->end_at) }} {{ __('common.minutes') }}</p>
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
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Lesson Mode') }}</p>
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
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Location') }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $booking->location->name }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Meeting Link / Location Details -->
            @if($booking->lesson_mode->value === 'online')
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('common.Meeting Link') }}
                        </h3>
                        @if($booking->meeting_url)
                            <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-xl">
                                <a href="{{ $booking->meeting_url }}" target="_blank" class="flex items-center gap-2 text-blue-700 hover:text-blue-800 font-medium break-all">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    {{ $booking->meeting_url }}
                                </a>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-900 mb-1">{{ __('common.Meeting URL Pending') }}</p>
                                        <p class="text-sm text-yellow-800">{{ __('common.The meeting URL will be provided by your teacher. Please check back later.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($booking->lesson_mode->value === 'in_person')
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('common.Meeting Location') }}
                        </h3>
                        @if($booking->location)
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-400 rounded-xl">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide mb-1">{{ __('common.Location Name') }}</p>
                                            <p class="text-sm font-bold text-emerald-900">{{ $booking->location->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide mb-1">{{ __('common.Address') }}</p>
                                            <p class="text-sm text-emerald-900">{{ $booking->location->address }}</p>
                                        </div>
                                        @if($booking->location->map_url)
                                            <div>
                                                <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide mb-2">{{ __('common.Map Location') }}</p>
                                                <a href="{{ $booking->location->map_url }}" target="_blank" class="inline-flex items-center gap-2 text-emerald-700 hover:text-emerald-800 font-medium break-all">
                                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                    {{ __('common.View on Map') }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($booking->location->notes)
                                            <div class="pt-3 border-t border-emerald-200">
                                                <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide mb-1">{{ __('common.Additional Notes') }}</p>
                                                <p class="text-sm text-emerald-900 whitespace-pre-wrap">{{ $booking->location->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-900 mb-1">{{ __('common.Location Pending') }}</p>
                                        <p class="text-sm text-yellow-800">{{ __('common.The meeting location will be specified by your teacher. Please check back later.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                            {{ __('common.Notes') }}
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
                            {{ __('common.Cancellation Reason') }}
                        </h3>
                        <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-xl">
                            <p class="text-sm text-red-900 whitespace-pre-wrap">{{ $booking->cancellation_reason }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @php
                            $conversation = \App\Models\Conversation::where(function($q) use ($booking) {
                                $q->where('user_one_id', auth()->id())
                                  ->where('user_two_id', $booking->teacher->user_id)
                                  ->where('booking_id', $booking->id);
                            })->orWhere(function($q) use ($booking) {
                                $q->where('user_one_id', $booking->teacher->user_id)
                                  ->where('user_two_id', auth()->id())
                                  ->where('booking_id', $booking->id);
                            })->first();
                        @endphp
                        @if($conversation)
                            <a href="{{ route('student.messages.show', $conversation) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                {{ __('common.Message Teacher') }}
                            </a>
                        @else
                            <a href="{{ route('student.messages.create', ['booking_id' => $booking->id]) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                {{ __('common.Start Conversation') }}
                            </a>
                        @endif
                        @if($booking->isAwaitingPayment())
                            <a href="{{ route('student.bookings.pay', $booking) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('common.Complete Payment') }}
                            </a>
                        @endif
                        @if(!$booking->isCancelled() && !$booking->isCompleted())
                            <button type="button" onclick="document.getElementById('cancelModal').classList.remove('hidden')" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border-2 border-red-300 bg-red-50 text-red-700 rounded-xl text-sm font-semibold hover:bg-red-100 hover:border-red-400 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('common.Cancel Booking') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cancel Booking Modal -->
            @if(!$booking->isCancelled() && !$booking->isCompleted())
                <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.Cancel Booking') }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ __('common.Are you sure you want to cancel this booking? Please provide a reason for cancellation.') }}</p>
                        <form method="POST" action="{{ route('student.bookings.cancel', $booking) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="cancellation_reason" class="block text-sm font-semibold text-gray-900 mb-2">
                                    {{ __('common.Cancellation Reason') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                                    class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition resize-none @error('cancellation_reason') border-red-500 @enderror"
                                    placeholder="{{ __('common.Please explain why you are cancelling this booking...') }}">{{ old('cancellation_reason') }}</textarea>
                                @error('cancellation_reason')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                    {{ __('common.Cancel') }}
                                </button>
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-red-700 hover:to-red-800 transition">
                                    {{ __('common.Confirm Cancellation') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Resources Section -->
            @if($booking->resources->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('common.Learning Resources') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($booking->resources as $resource)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 mb-1 truncate">{{ $resource->title }}</h4>
                                            @if($resource->description)
                                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $resource->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span>{{ $resource->file_name }}</span>
                                                <span>•</span>
                                                <span>{{ $resource->file_size_human }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('student.resources.download', $resource) }}" class="flex-shrink-0 inline-flex items-center px-3 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            {{ __('common.Download') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('student.resources.index', ['booking_id' => $booking->id]) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                                {{ __('common.View All Resources →') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Teacher Reviews Section -->
            @php
                $teacherReviews = $booking->teacher->reviews()
                    ->where('is_approved', true)
                    ->with('user')
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp
            @if($teacherReviews->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            {{ __('common.Teacher Reviews') }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($teacherReviews as $review)
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $review->user->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->format('M j, Y') }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm text-gray-700 mt-2">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($booking->teacher->reviewsCount() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('student.subjects.show', $booking->subject) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                                    {{ __('common.View All') }} {{ $booking->teacher->reviewsCount() }} {{ __('common.Reviews →') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Review Section -->
            @if($booking->isCompleted())
                @php
                    $existingReview = $booking->reviews()->where('user_id', auth()->id())->first();
                @endphp
                @if(!$existingReview)
                    <div id="review" class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                {{ __('common.Rate This Lesson') }}
                            </h3>
                            <form method="POST" action="{{ route('student.reviews.store') }}">
                                @csrf
                                <input type="hidden" name="reviewable_type" value="App\Models\Booking">
                                <input type="hidden" name="reviewable_id" value="{{ $booking->id }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('common.Rating') }}</label>
                                    <div class="flex items-center gap-2" id="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" onclick="setRating({{ $i }})" class="star-rating text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="{{ $i }}">
                                                ★
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating" value="5" required>
                                </div>
                                <div class="mb-4">
                                    <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">{{ __('common.Your Review (optional)') }}</label>
                                    <textarea name="comment" id="comment" rows="4" class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition resize-none" placeholder="{{ __('common.Share your experience...') }}"></textarea>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition">
                                    {{ __('common.Submit Review') }}
                                </button>
                            </form>
                            <script>
                                function setRating(rating) {
                                    document.getElementById('rating').value = rating;
                                    document.querySelectorAll('.star-rating').forEach((star, index) => {
                                        if (index < rating) {
                                            star.classList.remove('text-gray-300');
                                            star.classList.add('text-yellow-400');
                                        } else {
                                            star.classList.remove('text-yellow-400');
                                            star.classList.add('text-gray-300');
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                {{ __('common.Your Review') }}
                            </h3>
                            <div class="flex items-center gap-2 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            @if($existingReview->comment)
                                <p class="text-sm text-gray-700">{{ $existingReview->comment }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">{{ $existingReview->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>
                @endif
            @endif

            <!-- History -->
            @if($booking->histories->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('common.Booking History') }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($booking->histories as $history)
                                <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border-l-4 border-blue-500">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $history->action) }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $history->created_at->format('M j, Y g:i A') }}</p>
                                        @if($history->actor)
                                            <p class="text-xs text-gray-500 mt-1">{{ __('common.By:') }} {{ $history->actor->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
