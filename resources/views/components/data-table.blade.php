@props([
    'headers' => [],
    'caption' => null,
    'striped' => true,
    'hoverable' => true,
    'loading' => false,
    'emptyMessage' => null,
    'emptyTitle' => null,
    'emptyAction' => null,
    'emptyActionLabel' => null
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" role="table">
            @if($caption)
            <caption class="sr-only">{{ $caption }}</caption>
            @endif

            @if(count($headers) > 0)
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    @foreach($headers as $header)
                    <th
                        scope="col"
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        @if(is_array($header) && isset($header['width']))
                            style="width: {{ $header['width'] }}"
                        @endif
                    >
                        @if(is_array($header))
                            {{ $header['label'] ?? $header['name'] ?? '' }}
                        @else
                            {{ $header }}
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            @endif

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @if($loading)
                    @for($i = 0; $i < 5; $i++)
                    <tr class="animate-pulse">
                        @foreach($headers as $header)
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                        </td>
                        @endforeach
                    </tr>
                    @endfor
                @elseif($slot->isEmpty() && $emptyMessage)
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-4 py-12 text-center">
                            <x-empty-state
                                :title="$emptyTitle ?? __('No data found')"
                                :description="$emptyMessage"
                                :action="$emptyAction"
                                :actionLabel="$emptyActionLabel"
                            />
                        </td>
                    </tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>

            @isset($footer)
            <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                {{ $footer }}
            </tfoot>
            @endisset
        </table>
    </div>

    @isset($pagination)
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
        {{ $pagination }}
    </div>
    @endisset
</div>
