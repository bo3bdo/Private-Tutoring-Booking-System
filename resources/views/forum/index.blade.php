<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('forum.Forum') }}
        </h2>
    </x-slot>
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('forum.Forum') }}</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ __('forum.Join the conversation with students and teachers') }}</p>
        </div>

        <!-- Create Thread Button -->
        <div class="mb-6">
            <a href="{{ route('forum.create-thread') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('forum.Create New Thread') }}
            </a>
        </div>

        <!-- Forum Categories -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($categories as $category)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <!-- Category Header -->
                    <div class="flex items-start justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <a href="{{ route('forum.category', $category->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                {{ $category->name }}
                            </a>
                        </h2>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            @if($category->active_threads > 0)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full mr-2">
                                    {{ __('forum.Active') }}
                                </span>
                            @endif
                            <span>{{ $category->thread_count }}</span>
                        </div>
                    </div>

                    <!-- Category Description -->
                    @if($category->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $category->description }}</p>
                    @endif

                    <!-- Category Stats -->
                        <div class="flex items-center justify-between text-sm">
                        <div class="text-gray-600 dark:text-gray-400">
                            <span>{{ $category->thread_count }} {{ __('forum.threads') }}</span>
                            @if($category->post_count > 0)
                                <span class="mx-1">•</span>
                                <span>{{ $category->post_count }} {{ __('forum.posts') }}</span>
                            @endif
                        </div>
                        
                        <!-- Latest Post Info -->
                        @if($category->latestPost)
                            <div class="text-right">
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('forum.Latest Post') }}</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('forum.thread', $category->latestPost->thread->slug) }}#post-{{ $category->latestPost->id }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $category->latestPost->user->name }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $category->latestPost->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('forum.No posts yet') }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($categories->count() === 0)
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('forum.No Categories Yet') }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ __('forum.Forum categories haven\'t been created yet.') }}</p>
            </div>
        @endif
</x-app-layout>