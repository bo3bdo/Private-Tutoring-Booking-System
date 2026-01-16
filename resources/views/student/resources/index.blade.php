<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Resources') }}
                </h2>
                @if($booking ?? null)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Resources for:') }} {{ $booking->subject->name }} - {{ $booking->teacher->user->name }}</p>
                @elseif($course ?? null)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Resources for:') }} {{ $course->title }}</p>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.View and download learning resources') }}</p>
                @endif
            </div>
            @if($booking ?? null)
                <a href="{{ route('student.bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ __('common.Back to Booking') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 mb-6 p-4">
                <form method="GET" action="{{ route('student.resources.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search resources...') }}" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                    </div>
                    <div class="flex gap-2">
                        @if(!($booking ?? null) && !($course ?? null))
                            <select name="type" class="rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 text-sm text-gray-900 focus:border-emerald-400 dark:focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                <option value="">{{ __('common.All Types') }}</option>
                                <option value="App\Models\Booking" {{ request('type') === 'App\Models\Booking' ? 'selected' : '' }}>{{ __('common.Booking Resources') }}</option>
                                <option value="App\Models\Course" {{ request('type') === 'App\Models\Course' ? 'selected' : '' }}>{{ __('common.Course Resources') }}</option>
                            </select>
                        @endif
                        <button type="submit" class="px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                            {{ __('common.Filter') }}
                        </button>
                        @if(request()->hasAny(['search', 'type']) || ($booking ?? null) || ($course ?? null))
                            <a href="{{ route('student.resources.index') }}" class="px-4 py-2 border-2 border-slate-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 transition">
                                {{ __('common.Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($resources->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    @if($booking ?? null)
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No resources available for this booking') }}</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm">{{ __('common.Your teacher hasn\'t uploaded any resources yet for this lesson.') }}</p>
                    @elseif($course ?? null)
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">{{ __('common.No resources available for this course') }}</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm">{{ __('common.No resources have been uploaded for this course yet.') }}</p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('common.No resources available') }}</p>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($resources as $resource)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $resource->title }}</h3>
                                        @if($resource->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $resource->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-gray-700">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $resource->file_name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $resource->file_size_human }}</span>
                                    </div>
                                    <a href="{{ route('student.resources.download', $resource) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        {{ __('common.Download') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $resources->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
