@props([ 'tasks', 'sortBy', 'sortDirection' ])
<div>
        @if(count($tasks) > 0)
            <div class="divide-y-1 divide-natural-200  dark:divide-white/20">
                @foreach ($tasks as $task)
                <div class="flex py-2 items-center" wire:key="task-{{ $task->id }}" wire:loading.class="opacity-50" wire:target="open, close" wire:target="close">
                    <div class="mr-5 w-5">
                        <x-tasks.complete-button :task="$task" />
                    </div>
                    <div class="w-full">
                        <div class="w-full flex justify-between">
                            <div>{{ $task->name }}</div>
                            <div><x-priority-badge :priority="$task->priority" size="sm" iconOnly /></div>
                        </div>
                        <div class="w-full flex justify-between mt-2">
                            @if($task->due_date)
                                <flux:badge size="sm" icon="calendar-days">{{ $task->due_date->format('m/d/Y') }}</flux:badge>
                            @endif
                            @if($task->is_late)
                            <flux:badge size="sm" color="red"  icon="clock-alert">{{ __('Late') }}</flux:badge>
                            @endif

                        </div>
                    </div>
                    
                        
                </div>
                @endforeach
        </div>
        @else
            <div class="h-3/4 flex justify-center items-center">
                <div class="text-center">No task logged for this workstream</div>
            </div>
        @endif
</div>