<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Teachers Management') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Manage teachers and their profiles') }}</p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transform hover:scale-105 transition">
                <svg class="w-5 h-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('common.Add New Teacher') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->has('error'))
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Teacher') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Email') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Hourly Rate') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Subjects') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($teachers as $teacher)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->user->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $teacher->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $teacher->hourly_rate ? number_format($teacher->hourly_rate, 2).' BHD' : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            @if($teacher->subjects->isNotEmpty())
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($teacher->subjects->take(3) as $subject)
                                                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 text-xs font-semibold text-blue-800 dark:text-blue-300">
                                                            {{ $subject->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($teacher->subjects->count() > 3)
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">+{{ $teacher->subjects->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">{{ __('common.No subjects') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($teacher->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ $teacher->is_active ? __('common.Active') : __('common.Inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-semibold">{{ __('common.View') }}</a>
                                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold">{{ __('common.Edit') }}</a>
                                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('{{ __('common.Are you sure you want to delete this teacher?') }}')" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-semibold">{{ __('common.Delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 dark:text-gray-400 mb-2">No teachers found.</p>
                                                <a href="{{ route('admin.teachers.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">Add one</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $teachers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
