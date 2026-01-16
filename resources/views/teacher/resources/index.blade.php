<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Resources') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Manage learning resources for your students') }}</p>
            </div>
            <a href="{{ route('teacher.resources.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('common.Upload Resource') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($resources->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No resources uploaded yet') }}</p>
                    <a href="{{ route('teacher.resources.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                        {{ __('common.Upload Your First Resource') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($resources as $resource)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $resource->title }}</h3>
                                        @if($resource->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">{{ $resource->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $resource->file_name }}</span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">•</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $resource->file_size_human }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold {{ $resource->is_public ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300' }}">
                                                {{ $resource->is_public ? __('common.Public') : __('common.Private') }}
                                            </span>
                                            @if($resource->resourceable)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ class_basename($resource->resourceable_type) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-200 dark:border-gray-700">
                                    <form method="POST" action="{{ route('teacher.resources.destroy', $resource) }}" onsubmit="return confirm('{{ __('common.Are you sure you want to delete this resource?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 dark:bg-red-700 text-white rounded-xl text-sm font-semibold hover:bg-red-700 dark:hover:bg-red-600 transition">
                                            {{ __('common.Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $resources->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
