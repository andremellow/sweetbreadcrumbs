<section class="w-full">
    <x-heading heading="{{  __('Tasks') }}" subheading="{{ __('Turning To-Dos into Dones—One Task at a Time!') }}" >
        <flux:modal.trigger name="task-form-modal">
            <flux:button>{{ __('Create task') }}</flux:button>
        </flux:modal.trigger>
    </x-heading>
    <x-workstreams.layout :$workstream >
        <x-form.filter-form wire:submit="applyFilter" :isFiltred="$this->isFiltred"  >
            <x-form.filter-column span="2">
                <flux:input wire:model="search" :label="__('Name or Description')" type="text" />
            </x-form.filter-column>
            <x-form.filter-column span="2">
                <livewire:priority-dropdown wire:model.live="priorityId"/>
            </x-form.filter-column>
            <x-form.filter-column span="2">
                <flux:date-picker wire:model.live="dateRange"  :label="__('Due date')" mode="range" with-presets />
            </x-form.filter-column>
            <x-form.filter-column span="2">
                <flux:radio.group wire:model.live="status" size="sm" variant="segmented" :label="__('Status')">
                    <flux:radio label="All" value="all"/>
                    <flux:radio label="Open" value="open" icon="scan-line" />
                    <flux:radio label="Closed"  value="closed" icon="square-check-big" />
                </flux:radio.group>
            </x-form.filter-column>
            <x-form.filter-column span="2" class="flex items-end">
                <flux:button type="button" :loading="false" wire:click="toggleLate()" size="sm" variant="{{ $onlyLates ? 'danger' : 'filled' }}" icon="{{ $onlyLates ? 'clock-alert' : 'clock' }}">Lates</flux:button>
            </x-form.filter-column>
        </x-form.filter-form>
        @if(count($tasks) > 0)
            <div wire:loading.class="opacity-50">
                <x-tasks.table :$tasks :$sortBy :$sortDirection/>
            </div>
        @else
            <x-table-no-data />
        @endif
    </x-workstreams.layout>
    <livewire:task.task-modal :$workstream />
</section>
