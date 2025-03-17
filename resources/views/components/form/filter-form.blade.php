@props([
    'isFiltred' => false
])

<flux:accordion transition>
    <flux:accordion.item heading="Filter"  expanded>
        <div class="mt-5">
            <form {{ $attributes }}>
                <div class="flex-column sm:flex space-between gap-y-5 sm:gap-x-5 items-end">
                    <div class="flex-1 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        {{ $slot }}
                    </div>
                    <div class="flex h-full items-end justify-start gap-x-2 sm:col-span-1 sm:justify-end mt-5 sm:mt-">
                        <flux:button type="submit" icon="filter">
                            Filter
                        </flux:button>
                        
                        @if ($isFiltred)
                            <flux:button type="button" icon="filter-x" wire:click="$dispatch('reset')" />
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </flux:accordion.item>
</flux:accordion>