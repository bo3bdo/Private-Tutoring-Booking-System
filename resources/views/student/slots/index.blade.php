<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Available Slots - {{ $teacher->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $teacher->user->name }}</h1>
                            @if($subject)
                                <p class="text-gray-600 dark:text-gray-400">Subject: {{ $subject->name }}</p>
                            @endif
                        </div>
                        <div class="inline-flex rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-1">
                            <a href="?view=list&start={{ $startDate->format('Y-m-d') }}{{ $subject ? '&subject_id='.$subject->id : '' }}" class="px-3 py-1.5 text-sm rounded-lg {{ $view === 'list' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
                                List
                            </a>
                            <a href="?view=grid&start={{ $startDate->format('Y-m-d') }}{{ $subject ? '&subject_id='.$subject->id : '' }}" class="px-3 py-1.5 text-sm rounded-lg {{ $view === 'grid' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
                                Grid
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="?view={{ $view }}&start={{ $startDate->copy()->subWeek()->format('Y-m-d') }}{{ $subject ? '&subject_id='.$subject->id : '' }}" class="rounded-xl border border-slate-200 dark:border-gray-700 px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300">
                            Previous
                        </a>
                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-300">
                            {{ $startDate->format('M j') }} - {{ $startDate->copy()->addDays(6)->format('M j, Y') }}
                        </span>
                        <a href="?view={{ $view }}&start={{ $startDate->copy()->addWeek()->format('Y-m-d') }}{{ $subject ? '&subject_id='.$subject->id : '' }}" class="rounded-xl border border-slate-200 dark:border-gray-700 px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300">
                            Next
                        </a>
                    </div>
                </div>
            </div>

            @if($view === 'grid')
                @include('slots.partials._grid', ['slots' => $slots, 'subject' => $subject])
            @else
                @include('slots.partials._list', ['slots' => $slots, 'subject' => $subject])
            @endif
        </div>
    </div>
</x-app-layout>
