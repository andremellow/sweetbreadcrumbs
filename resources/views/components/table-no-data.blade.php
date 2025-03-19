@props(['showClear' => true])
<flux:card class="mt-5 justify-center text-center space-y-5 ">
    @if ($slot->isEmpty())
        <div>No results found for the search criteria</div>
    @else
        {{ $slot }}
    @endif
    @if($showClear)
    <flux:button icon="filter-x" wire:click="resetForm">Clear filter</flux:button>
    @endif
</flux:card>