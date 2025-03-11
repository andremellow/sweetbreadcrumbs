<section class="w-full">
    <x-meetings.heading >
        <flux:modal.trigger name="meeting-form-modal">
            <flux:button>{{ __('Create meeting') }}</flux:button>
        </flux:modal.trigger>
    </x-meetings.heading>
    <x-projects.layout :$project >
        <x-form.filter-form wire:submit="applyFilter" :isFiltred="$this->isFiltred"  >
            <x-form.filter-column span="3">
                <flux:input wire:model="search" :label="__('Name or Description')" type="text" />
            </x-form.filter-column>
            <x-form.filter-column span="2">
                <flux:date-picker wire:model="dateRange"  :label="__('Date')" mode="range" with-presets />
            </x-form.filter-column>
        </x-form.filter-form>
        @if(count($meetings) > 0)
            <x-meetings.table :$meetings :$sortBy :$sortDirection />
        @else
            <x-table-no-data />
        @endif
    </x-projects.layout>
    <livewire:meeting.meeting-modal />
</section>
