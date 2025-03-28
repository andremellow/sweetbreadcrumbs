<section class="w-full">
    <x-workstreams.heading/>

    <x-form.filter-form wire:submit="applyFilter" :isFiltred="$this->isFiltred" @reset="console.log('please reset the form')" >
        <x-form.filter-column span="2">
            <flux:input wire:model="name" :label="__('Name')" type="text" />
        </x-form.filter-column>
        <x-form.filter-column span="2">
            <livewire:priority-dropdown wire:model.live="priorityId"/>
        </x-form.filter-column>
        <x-form.filter-column span="1" class="hidden sm:flex" />
    </x-form.filter-form>
    @if(count($workstreams) > 0)
        <x-workstreams.table :$workstreams :$sortBy :$sortDirection />
    @else
        <x-table-no-data />
    @endif
</section>
