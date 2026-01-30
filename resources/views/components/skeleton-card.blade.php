@props([
    'lines' => 3,
    'showImage' => false,
    'showAvatar' => false,
    'showActions' => true
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden']) }}>
    <div class="p-6 animate-pulse">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-start gap-4">
                    {{-- Icon/Avatar skeleton --}}
                    @if($showAvatar)
                    <div class="flex-shrink-0 w-14 h-14 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                    @else
                    <div class="flex-shrink-0 w-14 h-14 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                    @endif

                    <div class="flex-1 min-w-0 space-y-3">
                        {{-- Title and badge --}}
                        <div class="flex items-center gap-3">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-32"></div>
                            <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-full w-20"></div>
                        </div>

                        {{-- Content lines --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @for($i = 0; $i < min($lines, 4); $i++)
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-{{ ['24', '32', '28', '20'][$i % 4] }}"></div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            @if($showActions)
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-xl w-28"></div>
                <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded-xl w-24"></div>
            </div>
            @endif
        </div>
    </div>
</div>
