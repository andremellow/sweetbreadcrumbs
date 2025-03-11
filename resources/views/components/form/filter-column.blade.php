@props(['span' => 3])

@php
$colSpanClasses = [
    1 => 'sm:col-span-1',
    2 => 'sm:col-span-2',
    3 => 'sm:col-span-3',
    4 => 'sm:col-span-4',
    5 => 'sm:col-span-5',
];
@endphp

<div {{ $attributes->merge(['class' => $colSpanClasses[$span] ?? 'sm:col-span-3']) }}>
    {{ $slot }}
</div>
