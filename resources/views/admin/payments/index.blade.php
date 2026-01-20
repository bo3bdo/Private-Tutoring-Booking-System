<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl lg:text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Payments Management') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.View and manage all payments') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-3 sm:p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total') }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-3 sm:p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Pending') }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-3 sm:p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Completed') }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-3 sm:p-4">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Total Amount') }}</p>
                    <p class="text-lg sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['total_amount'], 2) }} BHD</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-3 sm:p-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.payments.index') }}" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __('common.All') }}
                    </a>
                    <a href="{{ route('admin.payments.index', ['filter' => 'pending']) }}" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold {{ request('filter') === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __('common.Pending') }}
                    </a>
                    <a href="{{ route('admin.payments.index', ['filter' => 'succeeded']) }}" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold {{ request('filter') === 'succeeded' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __('common.Completed') }}
                    </a>
                    <a href="{{ route('admin.payments.index', ['filter' => 'failed']) }}" class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold {{ request('filter') === 'failed' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                        {{ __('common.Failed') }}
                    </a>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block sm:hidden space-y-3">
                @forelse($payments as $payment)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $payment->student->name }}</h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">{{ number_format($payment->amount, 2) }} BHD</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ml-2 flex-shrink-0
                                    @if($payment->status->value === 'succeeded') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @elseif($payment->status->value === 'failed') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                    @else bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                    @endif">
                                    {{ $payment->status->label() }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                {{ $payment->provider->label() }}
                            </div>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                {{ __('common.View') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 p-8 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('common.No payments found.') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Student') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Amount') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">{{ __('common.Provider') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">{{ __('common.Date') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $payment->student->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate md:hidden">{{ $payment->provider->label() }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($payment->amount, 2) }} BHD</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $payment->provider->label() }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span class="inline-flex items-center rounded-full px-2 sm:px-2.5 py-0.5 text-xs font-semibold
                                                @if($payment->status->value === 'succeeded') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @elseif($payment->status->value === 'failed') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @else bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                                @endif">
                                                {{ $payment->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $payment->created_at->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-right text-sm font-medium">
                                            <a href="{{ route('admin.payments.show', $payment) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition">
                                                {{ __('common.View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('common.No payments found.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>

            <!-- Mobile Pagination -->
            <div class="block sm:hidden mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
