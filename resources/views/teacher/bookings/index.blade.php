<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('common.Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'upcoming']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'upcoming' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Upcoming') }}
                        </a>
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'past']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'past' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Past') }}
                        </a>
                        <a href="{{ route('teacher.bookings.index', ['filter' => 'confirmed']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('filter') === 'confirmed' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.Confirmed') }}
                        </a>
                        <a href="{{ route('teacher.bookings.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ !request('filter') ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600' }}">
                            {{ __('common.All') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($bookings as $booking)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $booking->subject->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Student') }}: {{ $booking->student->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->start_at->format('l, F j, Y \a\t g:i A') }}</p>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mt-2
                                        @if($booking->status->value === 'confirmed') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                        @elseif($booking->status->value === 'awaiting_payment') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                        @elseif($booking->status->value === 'cancelled') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                        @else bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-300
                                        @endif">
                                        {{ $booking->status->label() }}
                                    </span>
                                </div>
                                <a href="{{ route('teacher.bookings.show', $booking) }}" class="rounded-xl border border-slate-300 dark:border-gray-600 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700">
                                    {{ __('common.View') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-12 text-center">
                            <p class="text-gray-500 dark:text-gray-400">{{ __('common.No bookings found.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
