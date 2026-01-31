@props(['title' => null, 'description' => null, 'striped' => true, 'hoverable' => true, 'compact' => false, 'responsive' => true])

<div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
    @if($title)
    <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-gray-700">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                @if($description)
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $description }}</p>
                @endif
            </div>
            @isset($headerAction)
                {{ $headerAction }}
            @endisset
        </div>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </table>
    </div>

    @isset($footer)
    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-slate-200 dark:border-gray-700">
        {{ $footer }}
    </div>
    @endisset
</div>
