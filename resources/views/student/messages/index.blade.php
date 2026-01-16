<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('common.Messages') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Chat with your teachers') }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($conversations->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No conversations yet') }}</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('common.Start a conversation with your teacher from a booking page') }}</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="divide-y divide-slate-200 dark:divide-gray-700">
                        @foreach($conversations as $conversation)
                            @php
                                $otherUser = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;
                                $unreadCount = $conversation->unreadMessagesCountFor(auth()->user());
                                $latestMessage = $conversation->latestMessage;
                            @endphp
                            <a href="{{ route('student.messages.show', $conversation) }}" class="block p-6 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $otherUser->name }}</h3>
                                            @if($latestMessage)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $latestMessage->created_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                        @if($latestMessage)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $latestMessage->body }}</p>
                                        @endif
                                    </div>
                                    @if($unreadCount > 0)
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-600 text-white text-xs font-bold rounded-full">
                                                {{ $unreadCount }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="p-4 border-t border-slate-200 dark:border-gray-700">
                        {{ $conversations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
