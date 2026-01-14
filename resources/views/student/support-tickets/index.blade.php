<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    Support Tickets
                </h2>
                <p class="text-sm text-gray-600 mt-1">Get help with your account and bookings</p>
            </div>
            <a href="{{ route('student.support-tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <a href="{{ route('student.support-tickets.index') }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            All
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'open']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'open' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Open
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'in_progress']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'in_progress' ? 'bg-amber-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            In Progress
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'resolved']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'resolved' ? 'bg-green-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Resolved
                        </a>
                        <a href="{{ route('student.support-tickets.index', ['status' => 'closed']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'closed' ? 'bg-gray-600 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Closed
                        </a>
                    </div>
                </div>
            </div>

            @if($tickets->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg mb-2">No support tickets yet</p>
                    <a href="{{ route('student.support-tickets.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                        Create Ticket
                    </a>
                </div>
            @else
                <!-- Search -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 p-4">
                    <form method="GET" action="{{ route('student.support-tickets.index') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tickets..." class="flex-1 rounded-xl border-2 border-slate-200 px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 transition">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                            Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('student.support-tickets.index', ['status' => request('status')]) }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                                @elseif($ticket->status === 'in_progress') bg-amber-100 text-amber-800
                                                @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                            <span class="text-xs font-mono text-gray-500">{{ $ticket->ticket_number }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $ticket->description }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span>{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                                            @if($ticket->assignedTo)
                                                <span>Assigned to: {{ $ticket->assignedTo->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('student.support-tickets.show', $ticket) }}" class="flex-shrink-0 text-emerald-600 hover:text-emerald-700">
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
