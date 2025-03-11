@props([ 'projects', 'sortBy', 'sortDirection' ])
<div>
    
    <flux:table :paginate="$projects" class="w-full mt-5">
        <flux:table.columns>
            <flux:table.column>ID</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection->value" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'priority'" :direction="$sortDirection->value" wire:click="sort('priority')" class="hidden sm:table-cell">Priority</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection->value" wire:click="sort('date')" class="hidden sm:table-cell">Created at</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            
            @foreach ($projects as $project)
            <flux:table.row :key="$project->id" >
                
                <flux:table.cell class="flex items-center gap-3">
                    {{ $project->id }}
                </flux:table.cell>
                
                <flux:table.cell variant="strong" class="whitespace-nowrap">
                    <div class="flex space-x-3 items-center">
                        <div class="mt-1 block sm:hidden">
                            <x-priority-badge :priority="$project->priority" size="sm" iconOnly />
                        </div>
                        <div>{{ $project->name }}</div>
                    </div>
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">
                    <x-priority-badge :priority="$project->priority" />
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">{{ $project->created_at->format('m/d/Y') }}</flux:table.cell>
                
                <flux:table.cell>
                    
                    <flux:dropdown>
                        <flux:button   variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item wire:click="$dispatch('load-project-form-modal', { projectId: {{ $project->id }} })" icon="pencil-square" >{{ __('Edit Project') }}</flux:menu.item>
                            <flux:menu.item 
                            wire:click="delete({{ $project->id }})"
                            wire:confirm="Are you sure you want to delete this project?"
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