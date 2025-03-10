@props([
    'isFiltred' => false
])

<form {{ $attributes->merge(['class' => 'grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6']) }}>
    {{ $slot }}

    <div class="flex h-full items-end justify-start gap-x-2 sm:col-span-1 sm:justify-end">
        <x-button onclick="submit()" size="sm">
            <x-icons.icon-filter />
            Filter
        </x-button>
        
        @if ($isFiltred)
            <x-button type="button" size="sm" wire:click="$dispatch('reset')">
                <x-icons.icon-filter-x />
            </x-button>
        @endif
    </div>
</form>
