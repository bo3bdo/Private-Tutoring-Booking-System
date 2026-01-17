<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-emerald-600 to-purple-600 dark:from-blue-800 dark:via-emerald-800 dark:to-purple-800 p-8 mb-8">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-emerald-900/80 to-purple-900/80 dark:from-blue-950/90 dark:via-emerald-950/90 dark:to-purple-950/90 z-10"></div>
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2071&q=80')] bg-cover bg-center bg-no-repeat opacity-50 dark:opacity-30"></div>
            </div>

            <!-- Content -->
            <div class="relative z-20 flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-white leading-tight drop-shadow-lg">
                        {{ __('common.Messages') }}
                    </h2>
                    <p class="text-sm text-gray-100 dark:text-gray-200 mt-2 drop-shadow-md">{{ __('common.Chat with your students') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="w-24 h-24 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 dark:border-white/20 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($conversations->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-emerald-100 dark:from-blue-900/30 dark:to-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('common.No conversations yet') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('common.Start a conversation with your students') }}</p>
                    <a href="{{ route('teacher.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-emerald-700 transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ __('common.View Courses') }}
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-gray-700 bg-gradient-to-r from-slate-50 to-transparent dark:from-gray-900/50 dark:to-transparent">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Your Conversations') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Total') }}: {{ $conversations->total() }} {{ __('common.conversations') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-200 dark:divide-gray-700">
                        @foreach($conversations as $conversation)
                            @php
                                $otherUser = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;
                                $unreadCount = $conversation->unreadMessagesCountFor(auth()->user());
                                $latestMessage = $conversation->latestMessage;
                            @endphp
                            <a href="{{ route('teacher.messages.show', $conversation) }}" class="block p-6 hover:bg-gradient-to-r hover:from-blue-50 hover:to-emerald-50 dark:hover:from-gray-700/50 dark:hover:to-gray-700/50 transition group">
                                <div class="flex items-start gap-4">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                        @if($otherUser->isOnline())
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full" title="{{ __('common.Online') }}"></div>
                                        @else
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-gray-400 border-2 border-white dark:border-gray-800 rounded-full" title="{{ __('common.Offline') }}"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">{{ $otherUser->name }}</h3>
                                                @if($otherUser->isOnline())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                        {{ __('common.Online') }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($latestMessage)
                                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $latestMessage->created_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                        @if($latestMessage)
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate flex-1">
                                                    @if($latestMessage->sender_id === auth()->id())
                                                        <span class="text-gray-500 dark:text-gray-500">{{ __('common.You:') }}</span>
                                                    @endif
                                                    {{ $latestMessage->body }}
                                                </p>
                                                @if($latestMessage->attachments->isNotEmpty())
                                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 dark:text-gray-500 italic">{{ __('common.No messages yet') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        @if($unreadCount > 0)
                                            <span class="inline-flex items-center justify-center min-w-[24px] h-6 px-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($conversations->hasPages())
                        <div class="p-4 border-t border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900/50">
                            {{ $conversations->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        // Function to update online status
        function updateOnlineStatus() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            fetch('/api/user/online-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Online status updated:', data);
            })
            .catch(error => {
                console.error('Error updating online status:', error);
            });
        }

        // Update online status immediately on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateOnlineStatus();
        });

        // Update online status every 20 seconds to keep user online
        setInterval(function() {
            updateOnlineStatus();
        }, 20000);

        // Update online status indicators every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</x-app-layout>
