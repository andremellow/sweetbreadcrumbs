@props([
    'isFiltred' => false
])

<flux:accordion transition>
    <flux:accordion.item heading="Filter"  expanded>
        <div class="mt-5">
            <form {{ $attributes }}>
                <div class="flex-column space-between space-y-5 items-end">
                    <div class="flex-1 grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-4 xl:grid-cols-6">
                        {{ $slot }}
                    </div>
                    <flux:separator variant="subtle" />
                    <div class="flex h-full items-end justify-start gap-x-2 sm:col-span-1 sm:justify-end mt-5 sm:mt-0">
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