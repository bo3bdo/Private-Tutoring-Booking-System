<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('forum.Reply to Thread') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Thread Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $thread->title }}</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ Str::limit($thread->content, 200) }}</p>
        </div>

        <!-- Reply Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('forum.store-post', $thread->slug) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('forum.Your Reply') }}
                    </label>
                    <textarea id="content" name="content" rows="10" required maxlength="10000" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('forum.thread', $thread->slug) }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        {{ __('forum.Cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        {{ __('forum.Post Reply') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
