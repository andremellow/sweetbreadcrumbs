@props([ 'tasks', 'sortBy', 'sortDirection' ])
<div>
    
    <flux:table :paginate="$tasks" class="w-full table-auto mt-5">
        <flux:table.columns>
            <flux:table.column class="w-1/10">ID</flux:table.column>
            <flux:table.column class="w-4/10" sortable :sorted="$sortBy === 'name'" :direction="$sortDirection->value" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
            <flux:table.column class="w-2/10 hidden sm:table-cell"  sortable :sorted="$sortBy === 'priority'" :direction="$sortDirection->value" wire:click="sort('priority')" >Priority</flux:table.column>
            <flux:table.column class="w-2/10 hidden sm:table-cell" sortable :sorted="$sortBy === 'due_date'" :direction="$sortDirection->value" wire:click="sort('due_date')" >{{ __('Due date') }}</flux:table.column>
            <flux:table.column class="w-1/10" />
        </flux:table.columns>
        <flux:table.rows>
            
            @foreach ($tasks as $task)
            <flux:table.row :key="$task->id" >
                
                <flux:table.cell class="flex items-center gap-3 cursor-pointer">
                    @if($task->is_completed)
                        <flux:icon.circle-check class="text-green-500 dark:text-green-300" wire:click="open({{ $task->id }})" />
                    @else
                        <flux:icon.circle class="text-black-500 dark:text-black-300 hover:text-green-500 dark:hover:text-green-300 "  wire:click="close({{ $task->id }})"/>
                    @endif
                    
                </flux:table.cell>
                
                <flux:table.cell variant="strong" class="whitespace-nowrap">
                    <div class="mt-1 block md:hidden">
                        <x-priority-badge :priority="$task->priority" size="sm" iconOnly />
                    </div>
                    <div class="{{ $task->is_completed ? 'line-through' : '' }} {{ $task->is_late? 'text-red-300' : '' }}">{{ $task->name }}</div>
                </flux:table.cell>
                <flux:table.cell class="hidden md:table-cell">
                    <x-priority-badge :priority="$task->priority" />
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">{{ $task->due_date ? $task->due_date->format('m/d/Y') : '' }}</flux:table.cell>
                <flux:table.cell>
                    
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item wire:click="$dispatch('load-task-form-modal', { taskId: {{ $task->id }} })" icon="pencil-square" >{{ __('Edit Task') }}</flux:menu.item>
                            <flux:menu.item 
                            wire:click="delete({{ $task->id }})"
                            wire:confirm="Are you sure you want to delete this task?"
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