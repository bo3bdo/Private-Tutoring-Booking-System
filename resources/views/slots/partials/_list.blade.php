@php
    $groupedSlots = $slots->groupBy(function($slot) {
        return $slot->start_at->format('Y-m-d');
    });
@endphp

@forelse($groupedSlots as $date => $daySlots)
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="p-6">
            <div class="mt-6 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($date)->format('D, M j, Y') }}</h3>
                <span class="text-xs text-slate-500 dark:text-gray-400">{{ $daySlots->count() }} {{ __('common.slots') }}</span>
            </div>

            <div class="mt-2 grid gap-2">
                @foreach($daySlots as $slot)
                    <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $slot->start_at->format('g:i A') }} – {{ $slot->end_at->format('g:i A') }}
                                </div>
                                <div class="mt-1 text-xs text-slate-500 dark:text-gray-400">
                                    {{ __('common.Duration') }}: {{ $slot->start_at->diffInMinutes($slot->end_at) }} {{ __('common.minutes') }}
                                </div>
                                @if(isset($isTeacher) && $isTeacher)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                            @if($slot->status->value === 'available') bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300
                                            @elseif($slot->status->value === 'blocked') bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300
                                            @else bg-slate-100 dark:bg-gray-600 text-slate-700 dark:text-gray-300
                                            @endif">
                                            {{ $slot->status->label() }}
                                        </span>
                                        @if($slot->booking)
                                            <span class="ml-2 text-xs text-slate-600 dark:text-gray-400">{{ __('common.Student') }}: {{ $slot->booking->student->name }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if(isset($isTeacher) && $isTeacher)
                                <div class="flex gap-2">
                                    @if($slot->status->value === 'available')
                                        <form method="POST" action="{{ route('teacher.slots.block', $slot) }}">
                                            @csrf
                                            <button type="submit" class="rounded-xl bg-rose-600 dark:bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 dark:hover:bg-rose-600">
                                                {{ __('common.Block') }}
                                            </button>
                                        </form>
                                    @elseif($slot->status->value === 'blocked')
                                        <form method="POST" action="{{ route('teacher.slots.unblock', $slot) }}">
                                            @csrf
                                            <button type="submit" class="rounded-xl bg-emerald-600 dark:bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 dark:hover:bg-emerald-600">
                                                {{ __('common.Unblock') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                @if($slot->status->value === 'available')
                                    <a href="{{ route('student.bookings.create', $slot) }}?subject_id={{ $subject?->id }}" class="rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                                        {{ __('common.Book') }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <x-empty-state
            :title="__('common.No available slots')"
            :description="__('common.Try changing the week or teacher.')"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </x-slot>
        </x-empty-state>
    </div>
@endforelse
