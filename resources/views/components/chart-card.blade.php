@props(['title', 'description' => null, 'chartId' => 'chart', 'height' => '300'])

<div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
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

    <!-- Chart Container -->
    <div class="p-4 sm:p-6">
        <div style="height: {{ $height }}px; position: relative;">
            <canvas id="{{ $chartId }}" data-chart-id="{{ $chartId }}"></canvas>
        </div>
    </div>

    <!-- Footer Info (Optional) -->
    @isset($footer)
    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-slate-200 dark:border-gray-700">
        {{ $footer }}
    </div>
    @endisset
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
