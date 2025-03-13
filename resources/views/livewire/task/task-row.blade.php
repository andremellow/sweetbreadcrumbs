<flux:table.row wire:loading.class="opacity-50" wire:target="open, close">
    <flux:table.cell class="flex items-center gap-3 cursor-pointer">
        <div class="hidden" wire:loading.class.remove="hidden">
            <flux:icon.loading />
        </div>
        <div wire:loading.class="hidden">
            @if($task->is_completed)
                <flux:icon.circle-check class="text-green-500 dark:text-green-300" wire:click="open({{ $task->id }})" />
            @else
                <div class="group" >
                    <flux:icon.circle class="text-black-500 dark:text-black-300 hover:text-green-500 dark:hover:text-green-300 group-hover:hidden"/>
                    <flux:icon.circle-check wire:loading.class="hidden" class="text-green-500 dark:text-green-300 hidden group-hover:block" wire:click="close({{ $task->id }})" />
                </div>
            @endif
        </div>
        
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