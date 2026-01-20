<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Support Tickets') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Manage customer support requests') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 p-4">
                <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Status') }}</label>
                            <select name="status" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="">{{ __('common.All Statuses') }}</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>{{ __('common.Open') }}</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('common.In Progress') }}</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('common.Resolved') }}</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('common.Closed') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Priority') }}</label>
                            <select name="priority" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="">{{ __('common.All Priorities') }}</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>{{ __('common.Low') }}</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>{{ __('common.Medium') }}</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>{{ __('common.High') }}</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>{{ __('common.Urgent') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Assigned To') }}</label>
                            <select name="assigned_to" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="">{{ __('common.All Staff') }}</option>
                                @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get() as $admin)
                                    <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Search') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search') }}..." class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            {{ __('common.Apply Filters') }}
                        </button>
                        @if(request()->hasAny(['status', 'priority', 'assigned_to', 'search']))
                            <a href="{{ route('admin.support-tickets.index') }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                                {{ __('common.Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($tickets->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('common.No support tickets found') }}</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                @if($ticket->status === 'open') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                                @elseif($ticket->status === 'in_progress') bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300
                                                @elseif($ticket->status === 'resolved') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                @if($ticket->priority === 'urgent') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @elseif($ticket->priority === 'high') bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300
                                                @elseif($ticket->priority === 'medium') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                            <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $ticket->ticket_number }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">{{ $ticket->description }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-2">
                                                {{ __('common.By:') }} {{ $ticket->user->name }}
                                                @if($ticket->user->isTeacher())
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300">
                                                        {{ __('common.Teacher') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                                        {{ __('common.Student') }}
                                                    </span>
                                                @endif
                                            </span>
                                            <span>{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                                            @if($ticket->assignedTo)
                                                <span>{{ __('common.Assigned To') }}: {{ $ticket->assignedTo->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="flex-shrink-0 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
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
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
