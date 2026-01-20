<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Earnings') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.View your payment history and earnings') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 rounded-xl shadow-lg border-2 border-emerald-200 dark:border-emerald-700/50 p-4">
                    <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300 uppercase tracking-wide mb-1">{{ __('common.Today') }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($todayEarnings, 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl shadow-lg border-2 border-blue-200 dark:border-blue-700/50 p-4">
                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide mb-1">{{ __('common.This Week') }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($weekEarnings, 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl shadow-lg border-2 border-purple-200 dark:border-purple-700/50 p-4">
                    <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide mb-1">{{ __('common.This Month') }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($monthEarnings, 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/30 rounded-xl shadow-lg border-2 border-amber-200 dark:border-amber-700/50 p-4">
                    <p class="text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wide mb-1">{{ __('common.This Year') }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($yearEarnings, 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-xl shadow-lg border-2 border-indigo-200 dark:border-indigo-700/50 p-4">
                    <p class="text-xs font-semibold text-indigo-700 dark:text-indigo-300 uppercase tracking-wide mb-1">{{ __('common.Total') }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEarnings, 2) }} BHD</p>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-4">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total Payments') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPayments }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-4">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Average Payment') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($averagePayment, 2) }} BHD</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-4">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Succeeded') }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $succeededCount }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 overflow-hidden">
                <div class="p-4">
                    <form method="GET" action="{{ route('teacher.earnings.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Period') }}</label>
                            <select name="period" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 text-sm text-gray-900 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>{{ __('common.All Time') }}</option>
                                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>{{ __('common.Today') }}</option>
                                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>{{ __('common.This Week') }}</option>
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>{{ __('common.This Month') }}</option>
                                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>{{ __('common.This Year') }}</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Status') }}</label>
                            <select name="status" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 text-sm text-gray-900 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                <option value="">{{ __('common.All Statuses') }}</option>
                                <option value="succeeded" {{ $status === 'succeeded' ? 'selected' : '' }}>{{ __('common.Succeeded') }}</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>{{ __('common.Pending') }}</option>
                                <option value="initiated" {{ $status === 'initiated' ? 'selected' : '' }}>{{ __('common.Initiated') }}</option>
                                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>{{ __('common.Failed') }}</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                                {{ __('common.Filter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($bookings->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No payments found') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">{{ __('common.Payments will appear here once students complete bookings') }}</p>
                </div>
            @else
                <!-- Payments Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Booking') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Student') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Subject') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Paid At') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-gray-700">
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-mono text-gray-900 dark:text-white">#{{ $booking->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $booking->student->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->subject->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($booking->payment->amount ?? 0, 2) }} BHD</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($booking->payment->status === \App\Enums\PaymentStatus::Succeeded) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @elseif(in_array($booking->payment->status, [\App\Enums\PaymentStatus::Pending, \App\Enums\PaymentStatus::Initiated])) bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                                @else bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @endif">
                                                {{ __('common.' . $booking->payment->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $booking->payment->paid_at ? $booking->payment->paid_at->format('M j, Y g:i A') : '-' }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('teacher.bookings.show', $booking) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 text-sm font-semibold">
                                                {{ __('common.View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-gray-700">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
