<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('forum.home') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                {{ __('forum.Forum') }}
            </a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-900 dark:text-white font-medium">{{ $category->name }}</span>
        </nav>
    </div>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $category->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $threads->total() }} {{ __('forum.threads') }}{{ $search ? ' ' . __('forum.matching ":search"', ['search' => e($search)]) : '' }}</p>
        </div>
        <a href="{{ route('forum.create-thread') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('forum.New Thread') }}
        </a>
    </div>

    <!-- Threads List -->
    @if($threads->count() > 0)
        <div class="space-y-4">
            @foreach($threads as $thread)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <!-- Thread Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                <a href="{{ route('forum.thread', $thread->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    @if($thread->is_pinned)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full mr-2">
                                            📌 {{ __('forum.Pinned') }}
                                        </span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full mr-2">
                                            🔒 {{ __('forum.Locked') }}
                                        </span>
                                    @endif
                                    {{ $thread->title }}
                                </a>
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('forum.by :user', ['user' => $thread->user->name]) }} • 
                                {{ $thread->created_at->diffForHumans() }} • 
                                {{ $thread->posts_count }} {{ $thread->posts_count == 1 ? __('forum.reply') : __('forum.replies') }}
                            </div>
                        </div>
                        
                        <!-- Vote Score -->
                        <div class="text-center ml-4 flex-shrink-0">
                            <div class="text-2xl font-bold {{ ($thread->upvotes_count - $thread->downvotes_count) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ ($thread->upvotes_count - $thread->downvotes_count) > 0 ? '+' : '' }}{{ $thread->upvotes_count - $thread->downvotes_count }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('forum.votes') }}</div>
                        </div>
                    </div>

                    <!-- Thread Preview -->
                    @if($thread->content)
                        <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-2">{{ Str::limit(strip_tags($thread->content), 200) }}</p>
                    @endif

                    <!-- Thread Stats -->
                    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 flex-wrap gap-2">
                        <div class="flex items-center space-x-4">
                            <span>👁️ {{ $thread->views_count }} {{ __('forum.views') }}</span>
                            <span>💬 {{ $thread->posts_count }} {{ __('forum.replies') }}</span>
                            @if($thread->best_answer_post_id)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                    ✓ {{ __('forum.Best Answer') }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs">
                            {{ __('forum.Last activity: :time', ['time' => $thread->last_post_at?->diffForHumans() ?? __('forum.Never')]) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $threads->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                @if($search)
                    {{ __('forum.No threads found matching ":search"', ['search' => e($search)]) }}
                @else
                    {{ __('forum.No threads in :category', ['category' => $category->name]) }}
                @endif
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                @if($search)
                    {{ __('forum.Try searching with different keywords.') }}
                @else
                    {{ __('forum.Be the first to start a conversation in this category!') }}
                @endif
            </p>
            <a href="{{ route('forum.create-thread') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                {{ __('forum.Create First Thread') }}
            </a>
        </div>
    @endif
</x-app-layout>
