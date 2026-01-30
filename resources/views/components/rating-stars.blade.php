@props([
    'rating' => 0,
    'count' => null,
    'size' => 'md',
    'showValue' => true,
    'interactive' => false,
    'name' => 'rating'
])

@php
$rating = floatval($rating);
$fullStars = floor($rating);
$hasHalfStar = ($rating - $fullStars) >= 0.5;
$emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

$sizeClasses = match($size) {
    'xs' => 'w-3 h-3',
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    'xl' => 'w-8 h-8',
    default => 'w-5 h-5',
};

$textSize = match($size) {
    'xs' => 'text-xs',
    'sm' => 'text-xs',
    'md' => 'text-sm',
    'lg' => 'text-base',
    'xl' => 'text-lg',
    default => 'text-sm',
};
@endphp

@if($interactive)
<div x-data="{ rating: {{ $rating }}, hoverRating: 0 }" class="flex items-center gap-1">
    @for($i = 1; $i <= 5; $i++)
    <button
        type="button"
        @click="rating = {{ $i }}"
        @mouseenter="hoverRating = {{ $i }}"
        @mouseleave="hoverRating = 0"
        class="focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded transition-transform hover:scale-110"
        :aria-label="'Rate ' + {{ $i }} + ' stars'"
    >
        <svg
            class="{{ $sizeClasses }} transition-colors duration-150"
            :class="(hoverRating || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-hidden="true"
        >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    </button>
    @endfor
    <input type="hidden" name="{{ $name }}" x-model="rating">
    <span x-show="rating > 0" class="{{ $textSize }} text-gray-600 dark:text-gray-400 ml-1" x-text="rating + '/5'"></span>
</div>
@else
<div class="flex items-center gap-1" role="img" aria-label="{{ $rating }} out of 5 stars">
    {{-- Full Stars --}}
    @for($i = 0; $i < $fullStars; $i++)
    <svg class="{{ $sizeClasses }} text-yellow-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
    @endfor

    {{-- Half Star --}}
    @if($hasHalfStar)
    <div class="relative">
        <svg class="{{ $sizeClasses }} text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <div class="absolute inset-0 overflow-hidden w-1/2">
            <svg class="{{ $sizeClasses }} text-yellow-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </div>
    </div>
    @endif

    {{-- Empty Stars --}}
    @for($i = 0; $i < $emptyStars; $i++)
    <svg class="{{ $sizeClasses }} text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
    </svg>
    @endfor

    {{-- Rating Value --}}
    @if($showValue && $rating > 0)
    <span class="{{ $textSize }} text-gray-600 dark:text-gray-400 ml-1">
        {{ number_format($rating, 1) }}
    </span>
    @endif

    {{-- Review Count --}}
    @if($count !== null)
    <span class="{{ $textSize }} text-gray-500 dark:text-gray-500">
        ({{ $count }})
    </span>
    @endif
</div>
@endif
