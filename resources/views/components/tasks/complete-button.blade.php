@props(['task'])
<div wire:loading.class="hidden">
    @if($task->is_completed)
        <flux:icon.circle-check class="text-green-500 dark:text-green-300" wire:click="open({{ $task->id }})" />
    @else
        <div class="group" >
            <flux:icon.circle class="text-black-500 dark:text-black-300 hover:text-green-500 dark:hover:text-green-300 group-hover:hidden"/>
            <flux:icon.circle-check wire:loading.class="hidden" class="text-green-500 cursor-pointer dark:text-green-300 hidden group-hover:block" wire:click="close({{ $task->id }})" />
        </div>
    @endif
</div>