@props([
    'user' => null,
    'name' => null,
    'src' => null,
    'size' => 'md',
    'showOnline' => false,
    'showName' => false,
    'namePosition' => 'right'
])

@php
$displayName = $name ?? $user?->name ?? 'User';
$initials = collect(explode(' ', $displayName))
    ->take(2)
    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
    ->join('');
$imageSrc = $src ?? $user?->avatar_url ?? $user?->profile_photo_url ?? null;
$isOnline = $showOnline && $user?->isOnline();

$sizeClasses = match($size) {
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-lg',
    '2xl' => 'w-20 h-20 text-xl',
    default => 'w-10 h-10 text-sm',
};

$onlineIndicatorSize = match($size) {
    'xs' => 'w-1.5 h-1.5',
    'sm' => 'w-2 h-2',
    'md' => 'w-2.5 h-2.5',
    'lg' => 'w-3 h-3',
    'xl' => 'w-3.5 h-3.5',
    '2xl' => 'w-4 h-4',
    default => 'w-2.5 h-2.5',
};

$colors = [
    '#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16',
    '#22C55E', '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9',
    '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF',
    '#EC4899', '#F43F5E'
];
$colorIndex = abs(crc32($displayName)) % count($colors);
$bgColor = $colors[$colorIndex];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    @if($showName && $namePosition === 'left')
        <span class="text-sm font-medium text-gray-900 dark:text-white truncate">
            {{ $displayName }}
        </span>
    @endif

    <div class="relative inline-block flex-shrink-0">
        @if($imageSrc)
            <img
                src="{{ $imageSrc }}"
                alt="{{ $displayName }}"
                class="{{ $sizeClasses }} rounded-full object-cover ring-2 ring-white dark:ring-gray-800"
                loading="lazy"
            >
        @else
            <div
                class="{{ $sizeClasses }} rounded-full flex items-center justify-center font-semibold text-white ring-2 ring-white dark:ring-gray-800"
                style="background-color: {{ $bgColor }}"
                role="img"
                aria-label="{{ $displayName }}"
            >
                {{ $initials }}
            </div>
        @endif

        @if($showOnline)
            <span
                class="absolute bottom-0 right-0 block {{ $onlineIndicatorSize }} rounded-full ring-2 ring-white dark:ring-gray-800 {{ $isOnline ? 'bg-green-500' : 'bg-gray-400' }}"
                aria-label="{{ $isOnline ? 'Online' : 'Offline' }}"
            ></span>
        @endif
    </div>

    @if($showName && $namePosition === 'right')
        <span class="text-sm font-medium text-gray-900 dark:text-white truncate">
            {{ $displayName }}
        </span>
    @endif

    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
