<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.support-tickets.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ $supportTicket->subject }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('common.Ticket #') }}{{ $supportTicket->ticket_number }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Ticket Info -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ __('common.Status') }}</p>
                        <form method="POST" action="{{ route('admin.support-tickets.update-status', $supportTicket) }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="rounded-xl border-2 border-slate-200 px-3 py-2 text-sm font-semibold
                                @if($supportTicket->status === 'open') bg-blue-100 text-blue-800
                                @elseif($supportTicket->status === 'in_progress') bg-amber-100 text-amber-800
                                @elseif($supportTicket->status === 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                <option value="open" {{ $supportTicket->status === 'open' ? 'selected' : '' }}>{{ __('common.Open') }}</option>
                                <option value="in_progress" {{ $supportTicket->status === 'in_progress' ? 'selected' : '' }}>{{ __('common.In Progress') }}</option>
                                <option value="resolved" {{ $supportTicket->status === 'resolved' ? 'selected' : '' }}>{{ __('common.Resolved') }}</option>
                                <option value="closed" {{ $supportTicket->status === 'closed' ? 'selected' : '' }}>{{ __('common.Closed') }}</option>
                            </select>
                        </form>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ __('common.Priority') }}</p>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                            @if($supportTicket->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($supportTicket->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($supportTicket->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($supportTicket->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ __('common.User') }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $supportTicket->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $supportTicket->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">{{ __('common.Assigned To') }}</p>
                        <form method="POST" action="{{ route('admin.support-tickets.assign', $supportTicket) }}" class="inline">
                            @csrf
                            <select name="assigned_to" onchange="this.form.submit()" class="w-full rounded-xl border-2 border-slate-200 px-3 py-2 text-sm">
                                <option value="">{{ __('common.Unassigned') }}</option>
                                @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->get() as $admin)
                                    <option value="{{ $admin->id }}" {{ $supportTicket->assigned_to === $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">{{ __('common.Description') }}</p>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $supportTicket->description }}</p>
                </div>
            </div>

            <!-- Replies -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.Conversation') }}</h3>
                <div class="space-y-4">
                    @foreach($supportTicket->replies as $reply)
                        <div class="p-4 rounded-xl {{ $reply->user_id === auth()->id() ? 'bg-blue-50 border border-blue-200' : ($reply->is_internal ? 'bg-gray-50 border border-gray-200' : 'bg-slate-50 border border-slate-200') }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-sm text-gray-900">{{ $reply->user->name }}</span>
                                    @if($reply->is_internal)
                                        <span class="text-xs bg-gray-200 text-gray-700 px-2 py-0.5 rounded">{{ __('common.Internal') }}</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.Add Reply') }}</h3>
                <form method="POST" action="{{ route('admin.support-tickets.reply', $supportTicket) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" rows="4" required class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition resize-none" placeholder="Type your reply..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_internal" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ __('common.Internal note (not visible to user)') }}</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 transition">
                        {{ __('common.Send Reply') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
