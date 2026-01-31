<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.My Subjects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ __('common.Select the subjects you can teach. Students will only see you for the subjects you select.') }}
                    </p>

                    <form method="POST" action="{{ route('teacher.subjects.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            @forelse($allSubjects as $subject)
                                <label class="flex items-start p-4 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                        {{ in_array($subject->id, $teacherSubjects) ? 'checked' : '' }}
                                        class="mt-1 rounded border-slate-300 dark:border-gray-600 text-slate-600 dark:text-gray-400 focus:ring-slate-500 dark:focus:ring-gray-400 dark:bg-gray-700">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $subject->name }}</p>
                                            @if(in_array($subject->id, $teacherSubjects))
                                                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-semibold text-green-800 dark:text-green-300">
                                                    {{ __('common.Selected') }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($subject->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $subject->description }}</p>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <x-empty-state
                                    :title="__('common.No Subjects Available')"
                                    :description="__('common.Please contact the administrator to add subjects.')"
                                >
                                    <x-slot name="icon">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </x-slot>
                                </x-empty-state>
                            @endforelse
                        </div>

                        @if($allSubjects->isNotEmpty())
                            <div class="mt-6 flex items-center gap-3">
                                <button type="submit" class="rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600">
                                    {{ __('common.Save Changes') }}
                                </button>
                                <a href="{{ route('teacher.dashboard') }}" class="rounded-xl border border-slate-300 dark:border-gray-600 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700">
                                    {{ __('common.Cancel') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
