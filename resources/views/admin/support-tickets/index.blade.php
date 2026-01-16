<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    Support Tickets
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage customer support requests</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-6 p-4">
                <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-xl border-2 border-slate-200 px-3 py-2 text-sm">
                                <option value="">All Statuses</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Priority</label>
                            <select name="priority" class="w-full rounded-xl border-2 border-slate-200 px-3 py-2 text-sm">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Assigned To</label>
                            <select name="assigned_to" class="w-full rounded-xl border-2 border-slate-200 px-3 py-2 text-sm">
                                <option value="">All Staff</option>
                                @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get() as $admin)
                                    <option value="{{ $admin->id }}" {{ request('assigned_to') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full rounded-xl border-2 border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['status', 'priority', 'assigned_to', 'search']))
                            <a href="{{ route('admin.support-tickets.index') }}" class="px-4 py-2 border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($tickets->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">No support tickets found</p>
                </div>
            @else
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
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                @if($ticket->priority === 'urgent') bg-red-100 text-red-800
                                                @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                                @elseif($ticket->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                            <span class="text-xs font-mono text-gray-500">{{ $ticket->ticket_number }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $ticket->description }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span>By: {{ $ticket->user->name }}</span>
                                            <span>{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                                            @if($ticket->assignedTo)
                                                <span>Assigned to: {{ $ticket->assignedTo->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="flex-shrink-0 text-blue-600 hover:text-blue-700">
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
