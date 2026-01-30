@props([
    'header' => false,
    'align' => 'left',
    'nowrap' => false
])

@php
$tag = $header ? 'th' : 'td';
$alignClass = match($align) {
    'center' => 'text-center',
    'right' => 'text-right',
    default => 'text-left',
};
$wrapClass = $nowrap ? 'whitespace-nowrap' : '';
@endphp

<{{ $tag }}
    {{ $attributes->merge(['class' => "px-4 py-4 text-sm text-gray-900 dark:text-gray-100 {$alignClass} {$wrapClass}"]) }}
    @if($header)
        scope="col"
    @endif
>
    {{ $slot }}
</{{ $tag }}>
