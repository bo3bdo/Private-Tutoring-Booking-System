<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.my-courses.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $course->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('common.Course Progress') }}</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($progress, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 h-3 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar: Lessons List -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden sticky top-4">
                        <div class="p-4 border-b border-slate-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('common.Lessons') }}</h3>
                        </div>
                        <div class="max-h-[600px] overflow-y-auto">
                            @foreach($course->lessons as $index => $lesson)
                                @php
                                    $isCompleted = $lesson->isCompletedBy(auth()->user());
                                    $isCurrent = $currentLesson && $currentLesson->id === $lesson->id;
                                @endphp
                                <a href="{{ route('student.my-courses.lesson', ['course' => $course->slug, 'lesson' => $lesson->id]) }}" 
                                   class="block p-4 border-b border-slate-100 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700 transition {{ $isCurrent ? 'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-l-blue-600 dark:border-l-blue-500' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-semibold text-sm
                                            @if($isCompleted) bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300
                                            @elseif($isCurrent) bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300
                                            @else bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400
                                            @endif">
                                            @if($isCompleted)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $lesson->title }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $lesson->durationFormatted() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Main: Video Player -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $currentLesson->title }}</h1>
                            
                            @if($currentLesson->summary)
                                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $currentLesson->summary }}</p>
                            @endif

                            <!-- Video Player -->
                            <div class="mb-6 bg-black rounded-xl overflow-hidden aspect-video">
                                @if($currentLesson->video_provider->value === 'youtube')
                                    <iframe src="{{ str_replace('watch?v=', 'embed/', $currentLesson->video_url) }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                @elseif($currentLesson->video_provider->value === 'vimeo')
                                    <iframe src="{{ str_replace('vimeo.com/', 'player.vimeo.com/video/', $currentLesson->video_url) }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="autoplay; fullscreen; picture-in-picture" 
                                            allowfullscreen></iframe>
                                @else
                                    <video controls class="w-full h-full" id="lesson-video" data-lesson-id="{{ $currentLesson->id }}" data-duration="{{ $currentLesson->duration_seconds }}">
                                        <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                        {{ __('common.Your browser does not support the video tag.') }}
                                    </video>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3">
                                @if(!$currentLesson->isCompletedBy(auth()->user()))
                                    <form method="POST" action="{{ route('student.lessons.complete', $currentLesson) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-green-700 hover:to-green-800 dark:hover:from-green-600 dark:hover:to-green-500 transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ __('common.Mark as Completed') }}
                                        </button>
                                    </form>
                                @else
                                    <div class="inline-flex items-center px-6 py-3 bg-green-100 dark:bg-green-900/50 rounded-xl text-sm font-semibold text-green-800 dark:text-green-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('common.Completed') }}
                                    </div>
                                @endif

                                @if($course->lessons->where('id', '>', $currentLesson->id)->first())
                                    <a href="{{ route('student.my-courses.lesson', ['course' => $course->slug, 'lesson' => $course->lessons->where('id', '>', $currentLesson->id)->first()->id]) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-600 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-500 transition">
                                        {{ __('common.Next Lesson') }}
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-track progress every 15 seconds for video elements
        const video = document.getElementById('lesson-video');
        if (video && video.dataset.duration) {
            let lastUpdate = 0;
            video.addEventListener('timeupdate', function() {
                const currentTime = Math.floor(video.currentTime);
                if (currentTime - lastUpdate >= 15) {
                    lastUpdate = currentTime;
                    fetch('{{ route("student.lessons.progress", $currentLesson) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            watched_seconds: currentTime
                        })
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
