@props([
    'isFiltred' => false
])

<form {{ $attributes->merge(['class' => 'grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6']) }}>
    {{ $slot }}

    <div class="flex h-full items-end justify-start gap-x-2 sm:col-span-1 sm:justify-end">
        <flux:button type="submit" icon="filter">
            Filter
        </flux:button>
        
        @if ($isFiltred)
            <flux:button type="button" icon="filter-x" wire:click="$dispatch('reset')" />
        @endif
    </div>
</form>
