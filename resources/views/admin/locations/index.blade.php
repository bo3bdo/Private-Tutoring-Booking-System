<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl lg:text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('common.Locations Management') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('common.Manage meeting locations for in-person lessons') }}</p>
            </div>
            <a href="{{ route('admin.locations.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-700 dark:to-slate-600 rounded-lg sm:rounded-xl text-sm font-semibold text-white shadow-lg hover:from-slate-800 hover:to-slate-700 dark:hover:from-slate-600 dark:hover:to-slate-500 transform hover:scale-105 transition">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('common.Add New Location') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->has('error'))
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Mobile Card View -->
            <div class="block sm:hidden space-y-3">
                @forelse($locations as $location)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $location->name }}</h3>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate mt-0.5">{{ $location->address }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ml-2 flex-shrink-0
                                    @if($location->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ $location->is_active ? __('common.Active') : __('common.Inactive') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                                <span>{{ $location->bookings()->count() }} {{ __('common.booking(s)') }}</span>
                            </div>
                            <div class="flex items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('admin.locations.show', $location) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                    {{ __('common.View') }}
                                </a>
                                <a href="{{ route('admin.locations.edit', $location) }}" class="flex-1 text-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                    {{ __('common.Edit') }}
                                </a>
                                <button
                                    type="button"
                                    @click="$dispatch('open-dialog', { id: 'delete-location-{{ $location->id }}' })"
                                    class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition"
                                >
                                    {{ __('common.Delete') }}
                                </button>
                                <x-confirm-dialog
                                    id="delete-location-{{ $location->id }}"
                                    :title="__('common.Delete Location')"
                                    :message="__('common.Are you sure you want to delete this location? This action cannot be undone.')"
                                    :confirmText="__('common.Delete')"
                                    :action="route('admin.locations.destroy', $location)"
                                    method="DELETE"
                                />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-200 dark:border-gray-700">
                        <x-empty-state
                            :title="__('common.No Locations Found')"
                            :description="__('common.Get started by creating a new location for in-person lessons.')"
                            :action="route('admin.locations.create')"
                            :actionLabel="__('common.Add New Location')"
                        >
                            <x-slot:icon>
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </x-slot>
                        </x-empty-state>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Name') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">{{ __('common.Address') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Bookings') }}</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($locations as $location)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $location->name }}</div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400 truncate mt-0.5 md:hidden">{{ $location->address }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                                            <div class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $location->address }}</div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span class="inline-flex items-center rounded-full px-2 sm:px-2.5 py-0.5 text-xs font-semibold
                                                @if($location->is_active) bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ $location->is_active ? __('common.Active') : __('common.Inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $location->bookings()->count() }} {{ __('common.booking(s)') }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm font-medium">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <a href="{{ route('admin.locations.show', $location) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-semibold">{{ __('common.View') }}</a>
                                                <a href="{{ route('admin.locations.edit', $location) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold">{{ __('common.Edit') }}</a>
                                                <button
                                                    type="button"
                                                    @click="$dispatch('open-dialog', { id: 'delete-location-desktop-{{ $location->id }}' })"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-semibold"
                                                >
                                                    {{ __('common.Delete') }}
                                                </button>
                                                <x-confirm-dialog
                                                    id="delete-location-desktop-{{ $location->id }}"
                                                    :title="__('common.Delete Location')"
                                                    :message="__('common.Are you sure you want to delete this location? This action cannot be undone.')"
                                                    :confirmText="__('common.Delete')"
                                                    :action="route('admin.locations.destroy', $location)"
                                                    method="DELETE"
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <x-empty-state
                                                :title="__('common.No Locations Found')"
                                                :description="__('common.Get started by creating a new location for in-person lessons.')"
                                                :action="route('admin.locations.create')"
                                                :actionLabel="__('common.Add New Location')"
                                            >
                                                <x-slot:icon>
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </x-slot>
                                            </x-empty-state>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $locations->links() }}
                    </div>
                </div>
            </div>

            <!-- Mobile Pagination -->
            <div class="block sm:hidden mt-4">
                {{ $locations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
