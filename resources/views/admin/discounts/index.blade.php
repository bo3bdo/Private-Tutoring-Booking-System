<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('common.Discounts') }}
            </h2>
            <a href="{{ route('admin.discounts.create') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                {{ __('common.Create Discount') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <form method="GET" class="flex flex-col sm:flex-row gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.Search') }}"
                                class="flex-1 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm placeholder-gray-400 dark:placeholder-gray-400 focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                            <select name="status"
                                class="rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 sm:px-4 py-2.5 sm:py-3 text-base sm:text-sm focus:border-slate-400 dark:focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-gray-500 focus:ring-offset-2 transition">
                                <option value="">{{ __('common.All') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('common.Active') }}</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('common.Inactive') }}</option>
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition shadow-lg hover:shadow-xl">
                                {{ __('common.Filter') }}
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Code') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Value') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($discounts as $discount)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $discount->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $discount->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ ucfirst($discount->type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $discount->type === 'percentage' ? $discount->value.'%' : $discount->value.' BHD' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $discount->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' }}">
                                            {{ $discount->is_active ? __('common.Active') : __('common.Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:underline mr-3">{{ __('common.Edit') }}</a>
                                        <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('{{ __('common.Are you sure?') }}')" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:underline">{{ __('common.Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $discounts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
