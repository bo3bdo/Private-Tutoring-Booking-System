<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                <a href="{{ route('admin.subjects.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Subjects</a> / {{ $subject->name }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.subjects.edit', $subject) }}" class="rounded-xl bg-indigo-600 dark:bg-indigo-700 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 dark:hover:bg-indigo-600">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subject->name }}</h1>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold mt-2
                                @if($subject->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                @endif">
                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if($subject->description)
                        <div class="mb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Description</p>
                            <p class="text-gray-900 dark:text-white">{{ $subject->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Teachers</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $subject->teachers()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Bookings</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $subject->bookings()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($subject->teachers->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Teachers Teaching This Subject</h3>
                        <div class="space-y-3">
                            @foreach($subject->teachers as $teacher)
                                <div class="border-l-4 border-blue-500 dark:border-blue-600 pl-4 py-2">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $teacher->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $teacher->bio }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
