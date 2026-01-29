<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 dark:from-emerald-700 dark:via-teal-700 dark:to-cyan-700 p-8 mb-8">
            <div class="absolute inset-0 z-0 opacity-20">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('Leaderboard') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('Compete with other learners and climb the ranks') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 -mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- My Ranking Card -->
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-2xl shadow-lg border-2 border-emerald-200 dark:border-emerald-700/50 p-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg">
                            <div class="w-20 h-20 rounded-full bg-white dark:bg-gray-800 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">#{{ $userRank['rank'] ?? 0 }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Rank') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Level') }} {{ $level['current']['level'] }} - {{ $level['current']['name'] }}</p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                            <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-emerald-200 dark:border-emerald-700/50">
                                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($user->total_points) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Points') }}</p>
                            </div>
                            <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-emerald-200 dark:border-emerald-700/50">
                                <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $user->current_streak }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Day Streak') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('student.subjects.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-emerald-700 hover:to-teal-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            {{ __('Earn More Points') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- How to Earn Points -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        {{ __('How to Earn Points') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Book a Lesson') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('+10 points') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Complete a Lesson') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('+25 points') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Send a Message') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('+2 points') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ __('Leave a Review') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('+15 points') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 3 Podium -->
            @php
                $leaderboardCollection = collect($leaderboard);
                $top3 = $leaderboardCollection->take(3);
                $second = $top3->get(1);
                $first = $top3->get(0);
                $third = $top3->get(2);
            @endphp
            @if($leaderboardCollection->count() >= 3)
            <div class="bg-gradient-to-br from-yellow-50 via-orange-50 to-amber-50 dark:from-yellow-900/20 dark:via-orange-900/20 dark:to-amber-900/20 rounded-2xl shadow-lg border-2 border-yellow-200 dark:border-yellow-700/50 p-8">
                <h3 class="text-center text-xl font-bold text-gray-900 dark:text-white mb-8">{{ __('Top Learners') }}</h3>
                <div class="flex justify-center items-end gap-4 md:gap-8">
                    <!-- Second Place -->
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-white text-xl md:text-2xl font-bold shadow-lg mb-2">
                            {{ substr($second['name'], 0, 1) }}
                        </div>
                        <div class="w-20 md:w-24 h-24 md:h-32 bg-gradient-to-b from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-t-lg flex flex-col items-center justify-end pb-2 shadow-md">
                            <span class="text-2xl font-bold text-gray-700 dark:text-gray-300">2</span>
                        </div>
                        <p class="mt-2 font-semibold text-gray-900 dark:text-white text-sm md:text-base text-center truncate max-w-[100px]">{{ $second['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($second['points']) }}</p>
                    </div>

                    <!-- First Place -->
                    <div class="flex flex-col items-center -mt-4">
                        <div class="relative">
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-2xl md:text-3xl font-bold shadow-lg ring-4 ring-yellow-200 dark:ring-yellow-700/50">
                                {{ substr($first['name'], 0, 1) }}
                            </div>
                        </div>
                        <div class="w-24 md:w-28 h-32 md:h-40 bg-gradient-to-b from-yellow-300 to-yellow-400 dark:from-yellow-600 dark:to-yellow-700 rounded-t-lg flex flex-col items-center justify-end pb-2 shadow-lg mt-2">
                            <span class="text-3xl font-bold text-yellow-800 dark:text-yellow-200">1</span>
                        </div>
                        <p class="mt-2 font-bold text-gray-900 dark:text-white text-base md:text-lg text-center truncate max-w-[120px]">{{ $first['name'] }}</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 font-semibold">{{ number_format($first['points']) }}</p>
                    </div>

                    <!-- Third Place -->
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xl md:text-2xl font-bold shadow-lg mb-2">
                            {{ substr($third['name'], 0, 1) }}
                        </div>
                        <div class="w-20 md:w-24 h-20 md:h-24 bg-gradient-to-b from-orange-200 to-orange-300 dark:from-orange-600 dark:to-orange-700 rounded-t-lg flex flex-col items-center justify-end pb-2 shadow-md">
                            <span class="text-2xl font-bold text-orange-800 dark:text-orange-200">3</span>
                        </div>
                        <p class="mt-2 font-semibold text-gray-900 dark:text-white text-sm md:text-base text-center truncate max-w-[100px]">{{ $third['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($third['points']) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Full Leaderboard -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Full Leaderboard') }}
                    </h3>
                </div>
                <div class="divide-y divide-slate-200 dark:divide-gray-700">
                    @foreach($leaderboard as $index => $leader)
                    <div class="flex items-center gap-4 p-4 {{ $leader['id'] === $user->id ? 'bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50' }} transition">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $index < 3 ? 'bg-yellow-400 text-yellow-900' : ($index < 10 ? 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400') }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                            {{ substr($leader['name'], 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $leader['name'] }}</p>
                                @if($leader['id'] === $user->id)
                                <span class="text-xs px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 rounded-full">{{ __('You') }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($leader['points']) }} {{ __('points') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 dark:text-white">{{ number_format($leader['points']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('points') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
