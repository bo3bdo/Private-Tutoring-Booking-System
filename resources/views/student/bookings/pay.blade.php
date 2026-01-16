<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.bookings.show', $booking) }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.Complete Payment') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Booking Summary -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex-shrink-0 w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ __('common.Booking Summary') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('common.Review your booking details before payment') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Subject') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->subject->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Teacher') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 truncate">{{ $booking->teacher->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Date & Time') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $booking->start_at->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $booking->start_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('common.Amount') }}</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ number_format($booking->teacher->hourly_rate ?? 25.00, 2) }} BHD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('common.Select Payment Method') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('common.Choose your preferred payment gateway') }}</p>
                    </div>

                    <div class="space-y-3">
                        @if(app()->environment('local') || config('app.debug'))
                            <form method="POST" action="{{ route('payments.test.complete', $booking) }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transform hover:scale-105 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('common.Pay Now (Test Mode)') }}
                                </button>
                            </form>
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-3 bg-white text-gray-500 font-medium">{{ __('common.Or use real payment gateways') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('payments.stripe.create-checkout', $booking) }}">
                            @csrf
                            <input type="hidden" name="provider" value="stripe">
                            <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-indigo-700 hover:to-indigo-800 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l2.207-5.111c-.988-.515-2.51-1.002-4.064-1.343-.155-.032-.315-.06-.48-.085v-.08H9.228v.078c-.338.02-.664.047-.976.084-3.24.7-5.403 2.416-5.403 5.155 0 3.225 2.88 4.951 6.515 5.842 2.294.751 3.927 1.512 3.927 3.005 0 .98-.84 1.545-2.12 1.545-1.838 0-4.644-.921-6.36-1.796L2.5 20.48c1.145.628 3.285 1.224 5.642 1.53.155.032.315.06.48.085v.078h7.357v-.078c.34-.02.664-.047.976-.084 3.24-.7 5.403-2.416 5.403-5.155 0-3.225-2.88-4.951-6.515-5.842h-.001z"/>
                                </svg>
                                {{ __('common.Pay with Stripe') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('payments.stripe.create-checkout', $booking) }}">
                            @csrf
                            <input type="hidden" name="provider" value="benefitpay">
                            <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('common.Pay with BenefitPay') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
