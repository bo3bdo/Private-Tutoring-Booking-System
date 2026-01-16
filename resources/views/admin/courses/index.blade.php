<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('common.Courses Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Courses') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Teacher') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Subject') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Price') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($courses as $course)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $course->teacher->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $course->subject->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($course->price, 2) }} {{ $course->currency }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($course->is_published) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ $course->is_published ? __('common.Published') : __('common.Draft') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">{{ __('common.View') }}</a>
                                                <form method="POST" action="{{ route('admin.courses.toggle-publish', $course) }}" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                        {{ $course->is_published ? __('common.Unpublish') : __('common.Published') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">{{ __('common.No courses found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
