<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 dark:from-purple-800 dark:via-pink-800 dark:to-orange-700 p-8 mb-8">
            <!-- Background Pattern -->
            <div class="absolute inset-0 z-0 opacity-20">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('Gamification') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('Track your progress, earn points, and unlock achievements') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 -mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Level & Points Overview -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <!-- Level Badge -->
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                                <div class="w-28 h-28 rounded-full bg-white dark:bg-gray-800 flex flex-col items-center justify-center">
                                    <span class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $level['current']['level'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Level') }}</span>
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-yellow-400 rounded-full p-2 shadow-md">
                                <svg class="w-6 h-6 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center mt-3 font-semibold text-gray-900 dark:text-white">{{ $level['current']['name'] }}</p>
                    </div>

                    <!-- Progress & Stats -->
                    <div class="flex-1 w-full">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Progress to next level') }}</span>
                            <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-6">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-4 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-slate-200 dark:border-gray-700 text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($user->total_points) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Total Points') }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-slate-200 dark:border-gray-700 text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->current_streak }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Day Streak') }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-slate-200 dark:border-gray-700 text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unlockedAchievements }}/{{ $totalAchievements }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Achievements') }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-slate-200 dark:border-gray-700 text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">#{{ $userRank['rank'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ __('Rank') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('student.gamification.achievements') }}" class="group flex items-center gap-4 p-6 bg-gradient-to-r from-amber-50 to-transparent dark:from-amber-900/20 dark:to-transparent rounded-2xl border-2 border-amber-200 dark:border-amber-700/50 hover:border-amber-300 dark:hover:border-amber-600 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-amber-500 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-400 transition">{{ __('Achievements') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('View all achievements and badges') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('student.gamification.leaderboard') }}" class="group flex items-center gap-4 p-6 bg-gradient-to-r from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent rounded-2xl border-2 border-emerald-200 dark:border-emerald-700/50 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-emerald-500 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition">{{ __('Leaderboard') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('See how you rank against others') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('student.bookings.index') }}" class="group flex items-center gap-4 p-6 bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent rounded-2xl border-2 border-blue-200 dark:border-blue-700/50 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">{{ __('Book a Lesson') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Earn points by booking lessons') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Recent Achievements -->
            @php($unlockedAchievementsList = collect($achievements)->where('is_unlocked', true))
            @if($unlockedAchievementsList->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Recent Achievements') }}</h3>
                        <a href="{{ route('student.gamification.achievements') }}" class="text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300">
                            {{ __('View All') }}
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($unlockedAchievementsList->sortByDesc('unlocked_at')->take(6) as $achievement)
                        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-amber-50 to-transparent dark:from-amber-900/20 dark:to-transparent rounded-xl border border-amber-200 dark:border-amber-700/30">
                            <div class="flex-shrink-0 w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $achievement['name'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">+{{ $achievement['points'] }} {{ __('points') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Badges -->
            @if(count($badges) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('My Badges') }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        @foreach($badges as $badge)
                        <div class="flex items-center gap-3 px-4 py-2 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-full border border-purple-200 dark:border-purple-700/50">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white capitalize">{{ $badge['name'] }}</span>
                            <span class="text-xs px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full uppercase">{{ $badge['tier'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Points History -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Points History') }}</h3>
                </div>
                <div class="p-6">
                    @if($pointsHistory->count() > 0)
                    <div class="space-y-3">
                        @foreach($pointsHistory as $history)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $history->points > 0 ? 'bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($history->points > 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $history->description }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $history->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            <span class="text-lg font-bold {{ $history->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $history->points > 0 ? '+' : '' }}{{ $history->points }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No points history yet') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ __('Start booking lessons to earn points!') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Top Leaderboard Preview -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Top Learners') }}</h3>
                        <a href="{{ route('student.gamification.leaderboard') }}" class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                            {{ __('View Full Leaderboard') }}
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach(collect($leaderboard)->take(5) as $index => $leader)
                        <div class="flex items-center gap-4 p-4 {{ $leader['id'] === $user->id ? 'bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-700/50' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-xl">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $index < 3 ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                {{ substr($leader['name'], 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $leader['name'] }}
                                    @if($leader['id'] === $user->id)
                                    <span class="text-xs ml-2 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full">{{ __('You') }}</span>
                                    @endif
                                </p>
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
    </div>
</x-app-layout>
