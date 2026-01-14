<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Complete Payment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subject:</span>
                            <span class="font-semibold">{{ $booking->subject->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Teacher:</span>
                            <span class="font-semibold">{{ $booking->teacher->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date & Time:</span>
                            <span class="font-semibold">{{ $booking->start_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-semibold">{{ number_format($booking->teacher->hourly_rate ?? 25.00, 2) }} BHD</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Payment Method</h3>
                    <div class="space-y-3">
                        @if(app()->environment('local') || config('app.debug'))
                            <form method="POST" action="{{ route('payments.test.complete', $booking) }}">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                                    Pay Now (Test Mode)
                                </button>
                            </form>
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">Or use real payment gateways</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('payments.stripe.create-checkout', $booking) }}">
                            @csrf
                            <input type="hidden" name="provider" value="stripe">
                            <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                                Pay with Stripe
                            </button>
                        </form>

                        <form method="POST" action="{{ route('payments.stripe.create-checkout', $booking) }}">
                            @csrf
                            <input type="hidden" name="provider" value="benefitpay">
                            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                Pay with BenefitPay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
