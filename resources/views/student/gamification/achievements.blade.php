<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 dark:from-amber-700 dark:via-orange-700 dark:to-red-700 p-8 mb-8">
            <div class="absolute inset-0 z-0 opacity-20">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('Achievements & Badges') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('Unlock achievements and collect badges as you learn') }}</p>
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
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/30 dark:to-orange-900/30 rounded-2xl shadow-lg border-2 border-amber-200 dark:border-amber-700/50 p-6 text-center">
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ collect($achievements)->where('is_unlocked', true)->count() }}</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300 uppercase font-semibold">{{ __('Unlocked') }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-2xl shadow-lg border-2 border-purple-200 dark:border-purple-700/50 p-6 text-center">
                    <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($badges) }}</p>
                    <p class="text-sm text-purple-700 dark:text-purple-300 uppercase font-semibold">{{ __('Badges') }}</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-2xl shadow-lg border-2 border-emerald-200 dark:border-emerald-700/50 p-6 text-center">
                    <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(collect($achievements)->where('is_unlocked', true)->sum('points')) }}</p>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300 uppercase font-semibold">{{ __('Points Earned') }}</p>
                </div>
            </div>

            <!-- My Badges -->
            @if(count($badges) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('My Badges') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($badges as $badge)
                        @php
                        $tierColors = [
                            'bronze' => 'from-orange-400 to-orange-600',
                            'silver' => 'from-gray-300 to-gray-500',
                            'gold' => 'from-yellow-400 to-yellow-600',
                            'platinum' => 'from-cyan-400 to-blue-600',
                        ];
                        $tierColor = $tierColors[$badge['tier']] ?? 'from-purple-400 to-pink-600';
                        @endphp
                        <div class="flex flex-col items-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ $tierColor }} flex items-center justify-center shadow-lg mb-3">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-900 dark:text-white text-center capitalize">{{ $badge['name'] }}</p>
                            <span class="text-xs px-2 py-0.5 mt-1 rounded-full uppercase font-semibold
                                {{ $badge['tier'] === 'bronze' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300' : '' }}
                                {{ $badge['tier'] === 'silver' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900/50 dark:text-gray-300' : '' }}
                                {{ $badge['tier'] === 'gold' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                {{ $badge['tier'] === 'platinum' ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300' : '' }}
                            ">{{ $badge['tier'] }}</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">{{ $badge['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Available Badges -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Available Badges to Earn') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                        $tierColors = [
                            'bronze' => 'from-orange-400 to-orange-600',
                            'silver' => 'from-gray-300 to-gray-500',
                            'gold' => 'from-yellow-400 to-yellow-600',
                            'platinum' => 'from-cyan-400 to-blue-600',
                        ];
                        $userBadgeIds = collect($badges)->pluck('id')->toArray();
                        $lockedBadges = collect($availableBadges)->whereNotIn('id', $userBadgeIds);
                        @endphp
                        
                        @forelse($lockedBadges as $badge)
                        @php
                        $tierColor = $tierColors[$badge['tier']] ?? 'from-purple-400 to-pink-600';
                        $progress = min(100, round(($user->total_points / $badge['points_threshold']) * 100));
                        @endphp
                        <div class="flex flex-col items-center p-4 bg-gray-100 dark:bg-gray-700/50 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 opacity-75">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br {{ $tierColor }} flex items-center justify-center shadow-lg mb-3 opacity-50">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300 text-center">{{ $badge['name'] }}</p>
                            <span class="text-xs px-2 py-0.5 mt-1 rounded-full uppercase font-semibold
                                {{ $badge['tier'] === 'bronze' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300' : '' }}
                                {{ $badge['tier'] === 'silver' ? 'bg-gray-100 text-gray-700 dark:bg-gray-900/50 dark:text-gray-300' : '' }}
                                {{ $badge['tier'] === 'gold' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                {{ $badge['tier'] === 'platinum' ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300' : '' }}
                            ">{{ $badge['tier'] }}</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">{{ $badge['description'] }}</p>
                            <div class="w-full mt-3">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">{{ $progress }}%</span>
                                    <span class="text-gray-500 dark:text-gray-400">{{ $user->total_points }}/{{ $badge['points_threshold'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-gradient-to-r {{ $tierColor }} h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8">
                            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Congratulations!') }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ __('You have collected all available badges!') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- All Achievements -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('All Achievements') }}
                    </h3>
                </div>
                <div class="p-6">
                    @forelse($groupedAchievements as $category => $categoryAchievements)
                    <div class="mb-8 last:mb-0">
                        <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">{{ ucfirst($category) }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($categoryAchievements as $achievement)
                            <div class="flex items-center gap-4 p-4 rounded-xl border-2 {{ $achievement['is_unlocked'] ? 'bg-gradient-to-r from-amber-50 to-transparent dark:from-amber-900/20 dark:to-transparent border-amber-200 dark:border-amber-700/50' : 'bg-gray-50 dark:bg-gray-700/30 border-gray-200 dark:border-gray-600 opacity-60' }}">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center {{ $achievement['is_unlocked'] ? 'bg-amber-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    @if($achievement['is_unlocked'])
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white {{ $achievement['is_unlocked'] ? '' : 'text-gray-500 dark:text-gray-400' }}">{{ $achievement['name'] }}</p>
                                        @if($achievement['is_unlocked'])
                                        <span class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-full">{{ __('Unlocked') }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $achievement['description'] }}</p>
                                    @if($achievement['is_unlocked'] && $achievement['unlocked_at'])
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('Unlocked') }}: {{ $achievement['unlocked_at']->format('M d, Y') }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold {{ $achievement['is_unlocked'] ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' : 'bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        +{{ $achievement['points'] }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No achievements available yet') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
