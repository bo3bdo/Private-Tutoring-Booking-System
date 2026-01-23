<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.bookings.show', $booking) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ __('common.Complete Payment') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Booking Summary -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex-shrink-0 w-12 h-12 bg-slate-900 dark:bg-slate-700 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Booking Summary') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Review your booking details before payment') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Subject') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->subject->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Teacher') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $booking->teacher->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Date & Time') }}</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->start_at->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $booking->start_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Amount') }}</p>
                                <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">{{ number_format($booking->teacher->hourly_rate ?? 25.00, 2) }} BHD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount Code Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden" x-data="discountCode()">
                <div class="p-6 md:p-8">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ __('common.Have a discount code?') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Enter discount code') }}</p>
                    </div>

                    <div x-show="!applied" class="flex gap-3">
                        <div class="flex-1">
                            <input type="text" x-model="code" 
                                   placeholder="{{ __('common.Discount Code') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-0 transition">
                        </div>
                        <button type="button" @click="applyCode()" :disabled="loading || !code"
                                class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-emerald-800 transform hover:scale-105 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">{{ __('common.Apply') }}</span>
                            <span x-show="loading">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div x-show="errorMessage" x-text="errorMessage" class="mt-3 text-sm text-red-600 dark:text-red-400"></div>

                    <div x-show="applied" class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-700">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('common.Discount Applied') }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400" x-text="'Code: ' + code.toUpperCase()"></p>
                                </div>
                            </div>
                            <button type="button" @click="removeCode()" 
                                    class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                                {{ __('common.Remove') }}
                            </button>
                        </div>

                        <div class="space-y-2 p-4 bg-slate-50 dark:bg-gray-900/50 rounded-xl">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('common.Subtotal') }}:</span>
                                <span class="font-semibold text-gray-900 dark:text-white" x-text="subtotal + ' BHD'"></span>
                            </div>
                            <div class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400">
                                <span>{{ __('common.Discount') }}:</span>
                                <span class="font-semibold" x-text="'-' + discount + ' BHD'"></span>
                            </div>
                            <div class="pt-2 border-t border-slate-200 dark:border-gray-700 flex justify-between">
                                <span class="font-bold text-gray-900 dark:text-white">{{ __('common.Final Amount') }}:</span>
                                <span class="font-bold text-xl text-emerald-600 dark:text-emerald-400" x-text="finalAmount + ' BHD'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('common.Select Payment Method') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Choose your preferred payment gateway') }}</p>
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
                                    <div class="w-full border-t border-slate-300 dark:border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-3 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-medium">{{ __('common.Or use real payment gateways') }}</span>
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

    @push('scripts')
    <script>
        function discountCode() {
            return {
                code: '',
                loading: false,
                applied: false,
                errorMessage: '',
                subtotal: {{ number_format($booking->teacher->hourly_rate ?? 25.00, 2) }},
                discount: 0,
                finalAmount: {{ number_format($booking->teacher->hourly_rate ?? 25.00, 2) }},

                async applyCode() {
                    if (!this.code) return;
                    
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('{{ route("student.bookings.validate-discount", $booking) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                code: this.code
                            })
                        });

                        const data = await response.json();

                        if (data.valid) {
                            this.applied = true;
                            this.discount = parseFloat(data.discount_amount).toFixed(2);
                            this.finalAmount = parseFloat(data.final_amount).toFixed(2);
                        } else {
                            this.errorMessage = data.message;
                        }
                    } catch (error) {
                        this.errorMessage = '{{ __("common.An error occurred.") }}';
                    } finally {
                        this.loading = false;
                    }
                },

                removeCode() {
                    this.code = '';
                    this.applied = false;
                    this.discount = 0;
                    this.finalAmount = this.subtotal;
                    this.errorMessage = '';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
