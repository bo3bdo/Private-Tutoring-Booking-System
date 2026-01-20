<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('student.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('common.Teacher Request Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ __('common.Request Status') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('common.Submitted on') }} {{ $teacherRequest->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    <div>
                        @if($teacherRequest->isPending())
                            <span class="inline-flex items-center rounded-full px-4 py-2 bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-sm font-semibold">
                                {{ __('common.Pending') }}
                            </span>
                        @elseif($teacherRequest->isApproved())
                            <span class="inline-flex items-center rounded-full px-4 py-2 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-sm font-semibold">
                                {{ __('common.Approved') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full px-4 py-2 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 text-sm font-semibold">
                                {{ __('common.Rejected') }}
                            </span>
                        @endif
                    </div>
                </div>
                @if($teacherRequest->reviewed_at)
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('common.Reviewed on') }} {{ $teacherRequest->reviewed_at->format('M j, Y g:i A') }}
                            @if($teacherRequest->reviewedBy)
                                {{ __('common.by') }} {{ $teacherRequest->reviewedBy->name }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Request Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6 space-y-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('common.Request Details') }}</h3>

                @if($teacherRequest->qualifications)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Qualifications') }}</label>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $teacherRequest->qualifications }}</p>
                    </div>
                @endif

                @if($teacherRequest->experience)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Experience') }}</label>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $teacherRequest->experience }}</p>
                    </div>
                @endif

                @if($teacherRequest->bio)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Bio') }}</label>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $teacherRequest->bio }}</p>
                    </div>
                @endif

                @if($teacherRequest->subjects->isNotEmpty())
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Subjects') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($teacherRequest->subjects as $subject)
                                <span class="inline-flex items-center rounded-full px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-sm font-semibold">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($teacherRequest->hourly_rate)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Hourly Rate') }}</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ number_format($teacherRequest->hourly_rate, 2) }} BHD</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Teaching Options') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @if($teacherRequest->supports_online)
                                <span class="inline-flex items-center rounded-full px-3 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold">
                                    {{ __('common.Online') }}
                                </span>
                            @endif
                            @if($teacherRequest->supports_in_person)
                                <span class="inline-flex items-center rounded-full px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold">
                                    {{ __('common.In-Person') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($teacherRequest->admin_notes)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('common.Admin Notes') }}</label>
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">{{ $teacherRequest->admin_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
