<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Teacher Requests') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Manage teacher application requests') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">{{ __('common.Pending') }}</p>
                            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">{{ __('common.Approved') }}</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $approvedCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">{{ __('common.Rejected') }}</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $rejectedCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 p-4">
                <form method="GET" action="{{ route('admin.teacher-requests.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Status') }}</label>
                            <select name="status" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="">{{ __('common.All Statuses') }}</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('common.Pending') }}</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('common.Approved') }}</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('common.Rejected') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Search') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search by name, email, qualifications...') }}" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                {{ __('common.Apply Filters') }}
                            </button>
                        </div>
                    </div>
                    @if(request()->hasAny(['status', 'search']))
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.teacher-requests.index') }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                                {{ __('common.Clear') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if($requests->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('common.No teacher requests found') }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($requests as $request)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                @if($request->status === 'pending') bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300
                                                @elseif($request->status === 'approved') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->format('M j, Y g:i A') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $request->user->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $request->user->email }}</p>
                                        @if($request->subjects->isNotEmpty())
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach($request->subjects->take(3) as $subject)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold">
                                                        {{ $subject->name }}
                                                    </span>
                                                @endforeach
                                                @if($request->subjects->count() > 3)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs font-semibold">
                                                        +{{ $request->subjects->count() - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.teacher-requests.show', $request) }}" class="flex-shrink-0 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
