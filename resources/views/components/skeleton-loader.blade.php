@props([
    'type' => 'stats',
    'delay' => 0,
])

<div
    x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, {{ $delay }})"
    {{ $attributes->merge(['class' => 'relative']) }}
>
    {{-- Actual content (rendered first, hidden until loaded) --}}
    <div
        class="transition-opacity duration-300"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
    >
        {{ $slot }}
    </div>

    {{-- Skeleton placeholder (absolutely positioned overlay) --}}
    <div
        class="absolute inset-0 transition-opacity duration-300 pointer-events-none"
        :class="loaded ? 'opacity-0' : 'opacity-100'"
        x-show="!loaded"
        x-transition:leave="transition-opacity ease-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        @if($type === 'stats')
            <x-skeleton-stats :count="$count ?? 4" :cols="$cols ?? 4" />
        @elseif($type === 'card')
            <x-skeleton-card :lines="$lines ?? 3" :showAvatar="$showAvatar ?? false" :showActions="$showActions ?? true" />
        @elseif($type === 'table')
            <x-skeleton-table :rows="$rows ?? 5" :cols="$cols ?? 4" />
        @elseif($type === 'profile')
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden animate-pulse">
                <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @for($i = 0; $i < 4; $i++)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                            <div class="space-y-2">
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        @elseif($type === 'booking-list')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden animate-pulse">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                            <div class="space-y-2">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                            </div>
                        </div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @for($i = 0; $i < ($rows ?? 3); $i++)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-40"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-56"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        @elseif($type === 'quick-actions')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden animate-pulse">
                <div class="p-6 border-b border-slate-200 dark:border-gray-700">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-28"></div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @for($i = 0; $i < 4; $i++)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-2 border-gray-200 dark:border-gray-600">
                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        @elseif($type === 'gamification')
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl shadow-lg border-2 border-gray-200 dark:border-gray-600/50 overflow-hidden animate-pulse">
                <div class="p-6 border-b border-gray-200 dark:border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                            <div class="space-y-2">
                                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-28"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-40"></div>
                            </div>
                        </div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @for($i = 0; $i < 4; $i++)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-600 text-center">
                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-2"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-16 mx-auto mb-1"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-12 mx-auto"></div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        @else
            {{ $skeleton ?? '' }}
        @endif
    </div>
</div>
