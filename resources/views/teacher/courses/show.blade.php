<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.courses.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $course->title }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Course Info -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="flex items-start gap-6 mb-6">
                    @if($course->thumbnail_path)
                        <img src="{{ Storage::url($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-32 h-32 object-cover rounded-xl">
                    @endif
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
                        <p class="text-gray-600 mb-4">{{ $course->subject->name }}</p>
                        <div class="flex items-center gap-4">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                            <span class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold
                                @if($course->is_published) bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($course->description)
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $course->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lessons</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $course->lessons->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Enrollments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $course->enrollments->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($course->purchases()->whereHas('payment', fn($q) => $q->where('status', 'succeeded'))->with('payment')->get()->sum(fn($p) => $p->payment->amount ?? 0), 2) }} {{ $course->currency }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('teacher.courses.lessons', $course) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl text-sm font-semibold text-white hover:from-purple-700 hover:to-purple-800 transition">
                        Manage Lessons
                    </a>
                    <a href="{{ route('teacher.courses.sales', $course) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl text-sm font-semibold text-white hover:from-emerald-700 hover:to-emerald-800 transition">
                        View Sales
                    </a>
                    @if($course->is_published)
                        <form method="POST" action="{{ route('teacher.courses.unpublish', $course) }}" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 border-2 border-amber-300 bg-amber-50 text-amber-700 rounded-xl text-sm font-semibold hover:bg-amber-100 hover:border-amber-400 transition">
                                Unpublish
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('teacher.courses.publish', $course) }}" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 rounded-xl text-sm font-semibold text-white hover:from-green-700 hover:to-green-800 transition">
                                Publish
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
