<section class="w-full">
    <x-projects.heading/>

    <x-form.filter-form wire:submit="applyFilter" :isFiltred="$this->isFiltred" @reset="console.log('please reset the form')" >
        <x-form.filter-column span="2">
            <flux:input wire:model="name" :label="__('Name')" type="text" />
        </x-form.filter-column>
        <x-form.filter-column span="2">
            <livewire:priority-dropdown wire:model.live="priorityId"/>
        </x-form.filter-column>
        <x-form.filter-column span="1" class="hidden sm:flex" />
    </x-form.filter-form>
    @if(count($projects) > 0)
        <x-projects.table :$projects :$sortBy :$sortDirection />
    @else
    <flux:card class="mt-5 justify-center text-center space-y-5 ">
        <div>No results found for the search criteria</div>
        <flux:button icon="adjustments-horizontal" wire:click="resetForm">Clear filter</flux:button>
    </flux:card>
    @endif
</section>
