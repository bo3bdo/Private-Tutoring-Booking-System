<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 dark:from-indigo-800 dark:via-purple-900 dark:to-indigo-950 p-4 sm:p-6 lg:p-8 mb-4 sm:mb-6 lg:mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/90 via-purple-900/90 to-indigo-900/90 dark:from-indigo-950/95 dark:via-purple-950/95 dark:to-indigo-950/95 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat opacity-30 dark:opacity-20"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="font-semibold text-xl sm:text-2xl lg:text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Reports & Analytics') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-indigo-100 dark:text-indigo-200 mt-1 sm:mt-2 drop-shadow-md">
                        {{ __('common.Comprehensive analytics and performance reports') }}
                    </p>
                </div>
                <div class="hidden md:block flex-shrink-0 ml-4">
                    <div class="w-16 h-16 lg:w-24 lg:h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-xl lg:rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8 -mt-4 sm:-mt-6 lg:-mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                            class="flex-1 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                            class="flex-1 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition shadow-lg hover:shadow-xl">
                            {{ __('common.Filter') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl sm:rounded-2xl shadow-lg border-2 border-blue-200 dark:border-blue-700/50 p-4 sm:p-6">
                    <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">{{ __('common.Total Revenue') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($revenueReport['total_revenue'], 2) }} BHD</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-xl sm:rounded-2xl shadow-lg border-2 border-green-200 dark:border-green-700/50 p-4 sm:p-6">
                    <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">{{ __('common.Total Bookings') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $revenueReport['total_bookings'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl sm:rounded-2xl shadow-lg border-2 border-purple-200 dark:border-purple-700/50 p-4 sm:p-6">
                    <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1 sm:mb-2">{{ __('common.Average per Booking') }}</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $revenueReport['total_bookings'] > 0 ? number_format($revenueReport['total_revenue'] / $revenueReport['total_bookings'], 2) : 0 }} BHD</p>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6">{{ __('common.Revenue Chart') }}</h3>
                    <div class="relative" style="height: 400px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Teacher Performance Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6">{{ __('common.Teacher Performance') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Teacher') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Bookings') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Completion Rate') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Revenue') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Rating') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($teacherReport['teachers']->take(10) as $teacher)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $teacher['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $teacher['total_bookings'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $teacher['completion_rate'] >= 80 ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : ($teacher['completion_rate'] >= 50 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400') }}">
                                            {{ $teacher['completion_rate'] }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ number_format($teacher['revenue'], 2) }} BHD</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span>{{ $teacher['average_rating'] > 0 ? number_format($teacher['average_rating'], 1) : 'N/A' }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('revenueChart');
                if (ctx) {
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    const data = @json($revenueReport['daily_data']);
                    
                    const textColor = isDarkMode ? 'rgb(243, 244, 246)' : 'rgb(17, 24, 39)';
                    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                    const borderColor = isDarkMode ? 'rgb(99, 102, 241)' : 'rgb(59, 130, 246)';
                    const backgroundColor = isDarkMode ? 'rgba(99, 102, 241, 0.1)' : 'rgba(59, 130, 246, 0.1)';
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => {
                                const date = new Date(item.date);
                                return date.toLocaleDateString('{{ app()->getLocale() }}', { month: 'short', day: 'numeric' });
                            }),
                            datasets: [{
                                label: '{{ __("common.Revenue") }}',
                                data: data.map(item => parseFloat(item.total)),
                                borderColor: borderColor,
                                backgroundColor: backgroundColor,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: borderColor,
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: textColor,
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: isDarkMode ? 'rgba(31, 41, 55, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                    titleColor: textColor,
                                    bodyColor: textColor,
                                    borderColor: borderColor,
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: true
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                },
                                y: {
                                    ticks: {
                                        color: textColor,
                                        callback: function(value) {
                                            return value.toFixed(2) + ' BHD';
                                        }
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
