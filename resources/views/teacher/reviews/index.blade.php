<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.My Reviews') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.View all reviews from students') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Average Rating') }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($averageRating, 1) }}</p>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">{{ __('common.Total Reviews') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $reviewsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center gap-2 overflow-x-auto">
                        <a href="{{ route('teacher.reviews.index') }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('type') ? 'bg-slate-900 dark:bg-slate-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.All') }}
                        </a>
                        <a href="{{ route('teacher.reviews.index', ['type' => 'teacher']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('type') === 'teacher' ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Teacher') }}
                        </a>
                        <a href="{{ route('teacher.reviews.index', ['type' => 'booking']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('type') === 'booking' ? 'bg-green-600 dark:bg-green-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Bookings') }}
                        </a>
                        <a href="{{ route('teacher.reviews.index', ['type' => 'course']) }}" class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('type') === 'course' ? 'bg-purple-600 dark:bg-purple-700 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Courses') }}
                        </a>
                    </div>
                </div>
            </div>

            @if($reviews->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No reviews yet') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">{{ __('common.Reviews will appear here once students rate your services') }}</p>
                </div>
            @else
                <!-- Search -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 p-4">
                    <form method="GET" action="{{ route('teacher.reviews.index') }}" class="flex gap-4">
                        <input type="hidden" name="type" value="{{ request('type') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search reviews...') }}" class="flex-1 rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                            {{ __('common.Search') }}
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('teacher.reviews.index', ['type' => request('type')]) }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                                {{ __('common.Clear') }}
                            </a>
                        @endif
                    </form>
                </div>

                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</h3>
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                                        @if($review->reviewable_type === \App\Models\TeacherProfile::class) bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                                        @elseif($review->reviewable_type === \App\Models\Booking::class) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                        @else bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300
                                                        @endif">
                                                        @if($review->reviewable_type === \App\Models\TeacherProfile::class)
                                                            {{ __('common.Teacher') }}
                                                        @elseif($review->reviewable_type === \App\Models\Booking::class)
                                                            {{ __('common.Booking') }}
                                                        @else
                                                            {{ __('common.Course') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-3 whitespace-pre-wrap">{{ $review->comment }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">{{ $review->created_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
