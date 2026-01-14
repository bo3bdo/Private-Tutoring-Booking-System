<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('student.subjects.index') }}" class="text-blue-600 hover:text-blue-800">Subjects</a> / {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $subject->name }}</h1>
                    @if($subject->description)
                        <p class="mt-2 text-gray-600">{{ $subject->description }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Recorded Courses</h2>
                        <a href="{{ route('student.subjects.courses', $subject) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Browse Courses
                        </a>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Explore recorded courses for {{ $subject->name }}. Learn at your own pace with video lessons from expert teachers.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Available Teachers</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($teachers as $teacher)
                            @php
                                $rating = $teacher->averageRating();
                                $reviewsCount = $teacher->reviewsCount();
                            @endphp
                            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900">{{ $teacher->user->name }}</h3>
                                        @if($reviewsCount > 0)
                                            <div class="flex items-center gap-2 mt-2">
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= round($rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700">{{ round($rating) }}</span>
                                                <span class="text-xs text-gray-500">({{ $reviewsCount }} {{ $reviewsCount === 1 ? 'review' : 'reviews' }})</span>
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500 mt-2">No reviews yet</p>
                                        @endif
                                    </div>
                                </div>
                                @if($teacher->bio)
                                    <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ Str::limit($teacher->bio, 100) }}</p>
                                @endif
                                <div class="mt-4 flex items-center gap-2 flex-wrap">
                                    @if($teacher->supports_online)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">Online</span>
                                    @endif
                                    @if($teacher->supports_in_person)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">In Person</span>
                                    @endif
                                    @if($teacher->hourly_rate)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ number_format($teacher->hourly_rate, 2) }} BHD/hr</span>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('student.teachers.slots', $teacher) }}?subject_id={{ $subject->id }}" class="inline-flex items-center justify-center w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        View Available Slots
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-slate-600 text-lg font-semibold">No teachers available</p>
                                <p class="text-slate-500 text-sm mt-1">No teachers are currently available for this subject.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
