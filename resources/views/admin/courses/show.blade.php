<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $course->title }}</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Teacher</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->teacher->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Subject</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->subject->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Price</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($course->price, 2) }} {{ $course->currency }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.courses.toggle-publish', $course) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="inline-flex items-center px-6 py-3 {{ $course->is_published ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} rounded-xl text-sm font-semibold text-white transition">
                            {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Lessons</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->lessons->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Enrollments</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->enrollments->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($course->purchases()->whereHas('payment', fn($q) => $q->where('status', 'succeeded'))->with('payment')->get()->sum(fn($p) => $p->payment->amount ?? 0), 2) }} {{ $course->currency }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
