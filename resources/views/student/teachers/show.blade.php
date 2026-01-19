<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            <a href="{{ route('student.subjects.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('common.Subjects') }}</a> / {{ __('common.Teacher Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Teacher Header Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar & Basic Info -->
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                {{ substr($teacher->user->name, 0, 1) }}
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $teacher->user->name }}</h1>
                                    @if($reviewsCount > 0)
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ round($averageRating, 1) }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $reviewsCount }} {{ $reviewsCount === 1 ? __('common.review') : __('common.reviews') }})</span>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('common.No reviews yet') }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Bio -->
                            @if($teacher->bio)
                                <p class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">{{ $teacher->bio }}</p>
                            @endif

                            <!-- Tags -->
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                @if($teacher->supports_online)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-3 py-1.5 text-sm font-semibold text-blue-800 dark:text-blue-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('common.Online') }}
                                    </span>
                                @endif
                                @if($teacher->supports_in_person)
                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-3 py-1.5 text-sm font-semibold text-green-800 dark:text-green-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ __('common.In Person') }}
                                    </span>
                                @endif
                                @if($teacher->hourly_rate)
                                    <span class="inline-flex items-center rounded-full bg-amber-100 dark:bg-amber-900/50 px-3 py-1.5 text-sm font-semibold text-amber-800 dark:text-amber-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ number_format($teacher->hourly_rate, 2) }} BHD/hr
                                    </span>
                                @endif
                                @if($teacher->defaultLocation)
                                    <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/50 px-3 py-1.5 text-sm font-semibold text-purple-800 dark:text-purple-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $teacher->defaultLocation->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                @if($upcomingSlots->isNotEmpty())
                                    <a href="{{ route('student.teachers.slots', $teacher) }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 transition shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('common.View Available Slots') }}
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('student.messages.start') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $teacher->user->id }}">
                                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-slate-700 dark:bg-slate-600 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        {{ __('common.Send Message') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Subjects -->
                    @if($teacher->subjects->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('common.Subjects Taught') }}</h2>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($teacher->subjects as $subject)
                                        <a href="{{ route('student.subjects.show', $subject) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition font-medium">
                                            {{ $subject->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Courses -->
                    @if($courses->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('common.Recorded Courses') }}</h2>
                                    <a href="{{ route('student.subjects.show', $teacher->subjects->first()) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        {{ __('common.View All') }}
                                    </a>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($courses as $course)
                                        <a href="{{ route('student.courses.show', $course) }}" class="block p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition group">
                                            <div class="flex items-start justify-between mb-2">
                                                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $course->title }}</h3>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ Str::limit($course->description, 80) }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($course->price, 2) }} {{ $course->currency }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $course->lessons->count() }} {{ __('common.lessons') }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reviews -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ __('common.Reviews') }}
                                @if($reviewsCount > 0)
                                    <span class="text-base font-normal text-gray-500 dark:text-gray-400">({{ $reviewsCount }})</span>
                                @endif
                            </h2>
                            @if($reviews->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach($reviews as $review)
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-semibold">
                                                        {{ substr($review->user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</p>
                                                        <div class="flex items-center gap-1 mt-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($review->comment)
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-3">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __('common.No reviews yet') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.Quick Info') }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Total Bookings') }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->bookings()->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Completed Sessions') }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->bookings()->where('status', 'completed')->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Courses') }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $courses->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Subjects') }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacher->subjects->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Slots -->
                    @if($upcomingSlots->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.Upcoming Available Slots') }}</h3>
                                <div class="space-y-2">
                                    @foreach($upcomingSlots->take(5) as $slot)
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $slot->start_at->format('M j, Y') }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $slot->start_at->format('g:i A') }} - {{ $slot->end_at->format('g:i A') }}</p>
                                                </div>
                                                <a href="{{ route('student.teachers.slots', $teacher) }}?slot={{ $slot->id }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                    {{ __('common.Book') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($upcomingSlots->count() > 5)
                                    <a href="{{ route('student.teachers.slots', $teacher) }}" class="block mt-4 text-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        {{ __('common.View All Slots') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
