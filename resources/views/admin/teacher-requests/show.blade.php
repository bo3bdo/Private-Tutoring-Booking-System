<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.teacher-requests.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Teacher Request') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Request from') }} {{ $teacherRequest->user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Request Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Status') }}</p>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                            @if($teacherRequest->status === 'pending') bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300
                            @elseif($teacherRequest->status === 'approved') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                            @else bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                            @endif">
                            {{ ucfirst($teacherRequest->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Submitted on') }}</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacherRequest->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @if($teacherRequest->reviewed_at)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Reviewed on') }}</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacherRequest->reviewed_at->format('M j, Y g:i A') }}</p>
                        </div>
                        @if($teacherRequest->reviewedBy)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Reviewed by') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacherRequest->reviewedBy->name }}</p>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- User Info -->
                <div class="pt-4 border-t border-slate-200 dark:border-gray-700 mb-4">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.User Information') }}</p>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $teacherRequest->user->name }}</p>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                            {{ __('common.Student') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $teacherRequest->user->email }}</p>
                </div>

                <!-- Request Details -->
                <div class="space-y-4">
                    @if($teacherRequest->qualifications)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Qualifications') }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">{{ $teacherRequest->qualifications }}</p>
                        </div>
                    @endif

                    @if($teacherRequest->experience)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Experience') }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">{{ $teacherRequest->experience }}</p>
                        </div>
                    @endif

                    @if($teacherRequest->bio)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Bio') }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">{{ $teacherRequest->bio }}</p>
                        </div>
                    @endif

                    @if($teacherRequest->subjects->isNotEmpty())
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Subjects') }}</p>
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
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Hourly Rate') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($teacherRequest->hourly_rate, 2) }} BHD</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('common.Teaching Options') }}</p>
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
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('common.Admin Notes') }}</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-200 dark:border-amber-800">{{ $teacherRequest->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions (only if pending) -->
            @if($teacherRequest->isPending())
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('common.Actions') }}</h3>
                    
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('admin.teacher-requests.approve', $teacherRequest) }}" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label for="approve_notes" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                {{ __('common.Admin Notes (Optional)') }}
                            </label>
                            <textarea name="admin_notes" id="approve_notes" rows="3" class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-green-400 dark:focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-400 dark:focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition resize-none" placeholder="{{ __('common.Add any notes about this approval...') }}"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-green-700 hover:to-green-800 transition">
                            {{ __('common.Approve Request') }}
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form method="POST" action="{{ route('admin.teacher-requests.reject', $teacherRequest) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="reject_notes" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                {{ __('common.Rejection Reason') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea name="admin_notes" id="reject_notes" rows="3" required class="w-full rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm placeholder-gray-400 focus:border-red-400 dark:focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 dark:focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition resize-none @error('admin_notes') border-red-500 @enderror" placeholder="{{ __('common.Please provide a reason for rejection...') }}"></textarea>
                            @error('admin_notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-sm font-semibold text-white shadow-lg hover:from-red-700 hover:to-red-800 transition">
                            {{ __('common.Reject Request') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
