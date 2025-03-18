@props([ 'tasks', 'sortBy', 'sortDirection', 'lastRender' ])
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
                <livewire:task.task-row :$task wire:key="task-{{ $task->id }}" />
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>