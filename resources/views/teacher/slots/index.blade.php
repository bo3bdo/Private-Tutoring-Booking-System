<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.Time Slots') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('common.Generate Slots') }}</h3>
                    </div>
                    <form method="POST" action="{{ route('teacher.slots.generate') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Start Date') }}</label>
                            <input type="date" name="start_date" value="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.End Date') }}</label>
                            <input type="date" name="end_date" value="{{ now()->addWeek()->format('Y-m-d') }}" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Duration (min)') }}</label>
                            <input type="number" name="duration_minutes" value="60" min="15" max="480" step="15" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Subject (Optional)') }}</label>
                            <select name="subject_id" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">
                                <option value="">{{ __('common.All Subjects') }}</option>
                                @foreach(auth()->user()->teacherProfile->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600">
                                {{ __('common.Generate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <a href="?view=list&start={{ $startDate->format('Y-m-d') }}" class="px-3 py-1.5 text-sm rounded-lg {{ $view === 'list' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300' }}">
                                {{ __('common.List') }}
                            </a>
                            <a href="?view=grid&start={{ $startDate->format('Y-m-d') }}" class="px-3 py-1.5 text-sm rounded-lg {{ $view === 'grid' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300' }}">
                                {{ __('common.Grid') }}
                            </a>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="?view={{ $view }}&start={{ $startDate->copy()->subWeek()->format('Y-m-d') }}" class="rounded-xl border border-slate-200 dark:border-gray-600 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700">
                                {{ __('common.Previous') }}
                            </a>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $startDate->format('M j') }} - {{ $startDate->copy()->addDays(6)->format('M j, Y') }}</span>
                            <a href="?view={{ $view }}&start={{ $startDate->copy()->addWeek()->format('Y-m-d') }}" class="rounded-xl border border-slate-200 dark:border-gray-600 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700">
                                {{ __('common.Next') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($view === 'grid')
                @include('slots.partials._grid', ['slots' => $slots, 'subject' => null, 'startDate' => $startDate, 'isTeacher' => true])
            @else
                @include('slots.partials._list', ['slots' => $slots, 'subject' => null, 'isTeacher' => true])
            @endif
        </div>
    </div>
</x-app-layout>
