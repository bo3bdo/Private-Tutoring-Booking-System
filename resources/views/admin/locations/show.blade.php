<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.locations.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    {{ $location->name }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.locations.edit', $location) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 rounded-xl text-sm font-semibold text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $location->name }}</h1>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($location->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ $location->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Address</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $location->address }}</p>
                            </div>
                        </div>

                        @if($location->map_url)
                            <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Map URL</p>
                                    <a href="{{ $location->map_url }}" target="_blank" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 break-all">
                                        {{ $location->map_url }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($location->notes)
                            <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-xl border border-slate-200 dark:border-gray-600">
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $location->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-gray-700">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Bookings</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $location->bookings()->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Teachers Using This Location</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $location->teacherProfiles()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
