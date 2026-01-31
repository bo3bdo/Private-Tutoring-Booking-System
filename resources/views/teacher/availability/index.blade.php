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
                    <form method="POST" action="{{ route('teacher.availability.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{ submitting: false }" x-on:submit="submitting = true">
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
                            <button type="submit" :disabled="submitting" class="w-full rounded-xl bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:hover:bg-slate-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!submitting">{{ __('common.Add') }}</span>
                                <span x-show="submitting" x-cloak class="inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
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
                                <button
                                    type="button"
                                    @click="$dispatch('open-dialog', { id: 'delete-availability-{{ $availability->id }}' })"
                                    class="rounded-xl bg-rose-600 dark:bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 dark:hover:bg-rose-600"
                                >
                                    {{ __('common.Remove') }}
                                </button>
                                <x-confirm-dialog
                                    id="delete-availability-{{ $availability->id }}"
                                    :title="__('common.Remove Availability')"
                                    :message="__('common.Remove this availability?')"
                                    :confirmText="__('common.Remove')"
                                    :action="route('teacher.availability.destroy', $availability)"
                                    method="DELETE"
                                />
                            </div>
                        @empty
                            <x-empty-state
                                :title="__('common.No Availability Set')"
                                :description="__('common.Add your available times above.')"
                            >
                                <x-slot name="icon">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </x-slot>
                            </x-empty-state>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
