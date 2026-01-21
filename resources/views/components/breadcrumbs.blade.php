@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        @foreach($items as $index => $item)
            <li class="flex items-center gap-2">
                @if($index > 0)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="hover:text-dark-blue-600 dark:hover:text-dark-blue-400 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="{{ $loop->last ? 'text-gray-900 dark:text-white font-medium' : '' }}">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
