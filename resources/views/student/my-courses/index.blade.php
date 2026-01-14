<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Recorded Courses
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($enrollments->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-2">You haven't enrolled in any courses yet.</p>
                    <a href="{{ route('student.subjects.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Browse Courses</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $progress = $course->progressPercentFor(auth()->user());
                        @endphp
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
                                <p class="text-sm text-gray-600 mb-4">{{ $course->subject->name }}</p>
                                
                                <!-- Progress Bar -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-700">Progress</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>

                                <a href="{{ route('student.my-courses.learn', $course->slug) }}" class="block w-full text-center px-4 py-2 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-sm font-semibold text-white hover:from-slate-800 hover:to-slate-700 transition">
                                    Continue Learning
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
