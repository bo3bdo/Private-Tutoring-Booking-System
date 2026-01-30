@props([
    'striped' => true,
    'hoverable' => true,
    'clickable' => false,
    'href' => null
])

@php
$classes = 'transition-colors duration-150';

if ($hoverable) {
    $classes .= ' hover:bg-gray-50 dark:hover:bg-gray-700/50';
}

if ($clickable || $href) {
    $classes .= ' cursor-pointer';
}
@endphp

@if($href)
<tr
    {{ $attributes->merge(['class' => $classes]) }}
    onclick="window.location='{{ $href }}'"
    role="link"
    tabindex="0"
    onkeydown="if(event.key === 'Enter') window.location='{{ $href }}'"
>
    {{ $slot }}
</tr>
@else
<tr {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</tr>
@endif
