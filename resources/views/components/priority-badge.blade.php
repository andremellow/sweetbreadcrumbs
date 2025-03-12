@props(['priority',  'iconOnly' => false])
@php
    $enum = App\Enums\PriorityEnum::from($priority->name);
@endphp

    <flux:badge 
        icon="{{ $enum->icon() }}"
        color="{{ $enum->color() }}" 
        {{ $attributes }}
    >{{ $priority->name }}</flux:badge>

