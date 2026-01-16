<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Support Tickets') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Get help with your account and bookings') }}</p>
            </div>
            <a href="{{ route('student.support-tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('common.New Ticket') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <a href="{{ route('student.support-tickets.index') }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-slate-900 dark:bg-slate-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.All') }}
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'open']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'open' ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Open') }}
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'in_progress']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'in_progress' ? 'bg-amber-600 dark:bg-amber-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.In Progress') }}
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'resolved']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'resolved' ? 'bg-green-600 dark:bg-green-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Resolved') }}
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'closed']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'closed' ? 'bg-gray-600 dark:bg-gray-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Closed') }}
                        </a>
                    </div>
                </div>
            </div>

            @if($tickets->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No support tickets yet') }}</p>
                    <a href="{{ route('student.support-tickets.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                        {{ __('common.Create Ticket') }}
                    </a>
                </div>
            @else
                <!-- Search -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 p-4">
                    <form method="GET" action="{{ route('student.support-tickets.index') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search tickets...') }}" class="flex-1 rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                            {{ __('common.Search') }}
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('student.support-tickets.index', ['status' => request('status')]) }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

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
                                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ __('common.' . str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                            <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $ticket->ticket_number }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">{{ $ticket->description }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                                            @if($ticket->assignedTo)
                                                <span>{{ __('common.Assigned to:') }} {{ $ticket->assignedTo->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('student.support-tickets.show', $ticket) }}" class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
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
