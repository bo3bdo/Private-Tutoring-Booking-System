<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.subjects.show', $subject) }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $subject->name }} - Recorded Courses
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($courses->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-2">No courses available for this subject yet.</p>
                    <a href="{{ route('student.subjects.show', $subject) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Back to Subject</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-xl transition">
                            @if($course->thumbnail_path)
                                <img src="{{ Storage::url($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $course->teacher->name }}
                                </p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-gray-900">{{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                                    <span class="text-sm text-gray-500">{{ $course->lessons->count() }} lessons</span>
                                </div>
                                <a href="{{ route('student.courses.show', $course->slug) }}" class="block w-full text-center px-4 py-2 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white hover:from-slate-800 hover:to-slate-700 transition">
                                    View Course
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
