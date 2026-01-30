@props([
    'rows' => 5,
    'cols' => 4
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            {{-- Header --}}
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr class="animate-pulse">
                    @for($i = 0; $i < $cols; $i++)
                    <th class="px-4 py-3">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </th>
                    @endfor
                </tr>
            </thead>

            {{-- Body --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @for($row = 0; $row < $rows; $row++)
                <tr class="animate-pulse">
                    @for($col = 0; $col < $cols; $col++)
                    <td class="px-4 py-4">
                        @if($col === 0)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
                        </div>
                        @elseif($col === $cols - 1)
                        <div class="flex items-center gap-2">
                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                        </div>
                        @else
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-{{ ['20', '28', '24', '32'][$col % 4] }}"></div>
                        @endif
                    </td>
                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
