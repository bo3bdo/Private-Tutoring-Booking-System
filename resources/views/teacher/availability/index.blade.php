<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.Availability') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.Add Availability') }}</h3>
                    <form method="POST" action="{{ route('teacher.availability.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Day') }}</label>
                            <select name="weekday" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                                <option value="0">{{ __('common.Sunday') }}</option>
                                <option value="1">{{ __('common.Monday') }}</option>
                                <option value="2">{{ __('common.Tuesday') }}</option>
                                <option value="3">{{ __('common.Wednesday') }}</option>
                                <option value="4">{{ __('common.Thursday') }}</option>
                                <option value="5">{{ __('common.Friday') }}</option>
                                <option value="6">{{ __('common.Saturday') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.Start Time') }}</label>
                            <input type="time" name="start_time" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('common.End Time') }}</label>
                            <input type="time" name="end_time" class="w-full rounded-lg border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600">
                                {{ __('common.Add') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('common.Current Availability') }}</h3>
                    <div class="space-y-3">
                        @forelse($availabilities as $availability)
                            <div class="flex items-center justify-between p-4 border border-slate-200 dark:border-gray-700 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ [__('common.Sunday'), __('common.Monday'), __('common.Tuesday'), __('common.Wednesday'), __('common.Thursday'), __('common.Friday'), __('common.Saturday')][$availability->weekday] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}</p>
                                </div>
                                <form method="POST" action="{{ route('teacher.availability.destroy', $availability) }}" onsubmit="return confirm('{{ __('common.Remove this availability?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-rose-600 dark:bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 dark:hover:bg-rose-600">
                                        {{ __('common.Remove') }}
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('common.No availability set. Add your available times above.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
