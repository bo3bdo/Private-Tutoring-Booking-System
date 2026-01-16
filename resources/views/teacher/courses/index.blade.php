<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('common.My Courses') }}
            </h2>
            <a href="{{ route('teacher.courses.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transition">
                <svg class="w-5 h-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('common.Create Course') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Course') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Subject') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Price') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Lessons') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Enrollments') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($courses as $course)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $course->subject->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($course->price, 2) }} {{ $course->currency }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $course->lessons->count() }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $course->enrollments->count() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($course->is_published) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ $course->is_published ? __('common.Published') : __('common.Draft') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('teacher.courses.show', $course) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">{{ __('common.View') }}</a>
                                                <a href="{{ route('teacher.courses.edit', $course) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">{{ __('common.Edit') }}</a>
                                                <a href="{{ route('teacher.courses.lessons', $course) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300">{{ __('common.Lessons') }}</a>
                                                <a href="{{ route('teacher.courses.sales', $course) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">{{ __('common.Sales') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <p class="text-gray-500 dark:text-gray-400">{{ __('common.No courses yet.') }} <a href="{{ route('teacher.courses.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">{{ __('common.Create one') }}</a></p>
                                        </td>
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
