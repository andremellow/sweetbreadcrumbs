@props([ 'workstreams', 'sortBy', 'sortDirection' ])
<div>
    
    <flux:table :paginate="$workstreams" class="w-full mt-5">
        <flux:table.columns>
            <flux:table.column class="w-1/10">ID</flux:table.column>
            <flux:table.column class="w-4/10" sortable :sorted="$sortBy === 'name'" :direction="$sortDirection->value" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column class="w-2/10 hidden sm:table-cell" sortable :sorted="$sortBy === 'priority'" :direction="$sortDirection->value" wire:click="sort('priority')">Priority</flux:table.column>
            <flux:table.column class="w-2/10 hidden sm:table-cell" sortable :sorted="$sortBy === 'date'" :direction="$sortDirection->value" wire:click="sort('date')">Created at</flux:table.column>
            <flux:table.column class="w-1/10 "/>
        </flux:table.columns>
        <flux:table.rows>
            
            @foreach ($workstreams as $workstream)
            <flux:table.row :key="$workstream->id" >
                
                <flux:table.cell class="flex items-center gap-3">
                    {{ $workstream->id }}
                </flux:table.cell>
                
                <flux:table.cell variant="strong" class="whitespace-nowrap">
                    <div class="flex space-x-3 items-center">
                        <div class="mt-1 block sm:hidden">
                            <x-priority-badge :priority="$workstream->priority" size="sm" iconOnly />
                        </div>
                        <div class="text-wrap">{{ $workstream->name }}</div>
                    </div>
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">
                    <x-priority-badge :priority="$workstream->priority" />
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">{{ $workstream->created_at->format('m/d/Y') }}</flux:table.cell>
                
                <flux:table.cell>
                    
                    <flux:dropdown>
                        <flux:button   variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item wire:click="$dispatch('{{ App\Enums\EventEnum::LOAD_WORKSTREAM_FORM_MODAL->value }}', { workstreamId: {{ $workstream->id }} })" icon="pencil-square" >{{ __('Edit Workstream') }}</flux:menu.item>
                            <flux:menu.item 
                            wire:click="delete({{ $workstream->id }})"
                            wire:confirm="Are you sure you want to delete this workstream?"
                            icon="trash"
                            variant="danger"
                            >Delete</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>