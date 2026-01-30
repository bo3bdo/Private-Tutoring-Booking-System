<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('forum.Search Results') }}
        </h2>
    </x-slot>

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('forum.search') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="{{ __('forum.Search forum...') }}" 
                    value="{{ $search }}"
                    class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            {{ __('forum.:count results for ":search"', ['count' => $threads->total(), 'search' => $search]) }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('forum.Searching in titles, content, posts, usernames, and categories') }}
        </p>
    </div>

    @if($threads->count() > 0)
        <div class="space-y-4">
            @foreach($threads as $thread)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <a href="{{ route('forum.thread', $thread->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $thread->title }}
                                    </a>
                                </h3>
                                @if($thread->is_pinned)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                        📌 {{ __('forum.Pinned') }}
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                        🔒 {{ __('forum.Locked') }}
                                    </span>
                                @endif
                                @if($thread->best_answer_post_id)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        ✓ {{ __('forum.Best Answer') }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('forum.in :category', ['category' => '']) }}<a href="{{ route('forum.category', $thread->category->slug) }}" class="hover:underline">{{ $thread->category->name }}</a> • 
                                {{ __('forum.by :user', ['user' => $thread->user->name]) }} • 
                                {{ $thread->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-2">{{ Str::limit(strip_tags($thread->content), 200) }}</p>

                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            {{ $thread->posts_count }} {{ __('forum.replies') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $thread->views_count }} {{ __('forum.views') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            {{ $thread->upvotes_count ?? 0 }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            {{ $thread->downvotes_count ?? 0 }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $threads->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('forum.No results found') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ __('forum.Try searching with different keywords.') }}</p>
        </div>
    @endif
</x-app-layout>
