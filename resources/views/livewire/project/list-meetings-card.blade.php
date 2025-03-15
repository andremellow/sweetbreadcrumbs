@props([ 'meetings', 'sortBy', 'sortDirection' ])

    @if(count($meetings) > 0)
        <flux:table class="w-full table-auto">
            <flux:table.columns>
                <flux:table.column class="w-5/8" >{{ __('Name') }}</flux:table.column>
                <flux:table.column class="w-1/8" class="hidden sm:table-cell">{{ __('Date') }}</flux:table.column>
                <flux:table.column class="w-1/8" />
            </flux:table.columns>
            <flux:table.rows>
                
                @foreach ($meetings as $meeting)
                <flux:table.row :key="$meeting->id" >
                    <flux:table.cell variant="strong" class="whitespace-nowrap">
                        <div>{{ $meeting->name }}</div>
                    </flux:table.cell>
                    <flux:table.cell class="hidden sm:table-cell">{{ $meeting->date->format('m/d/Y') }}</flux:table.cell>
                    <flux:table.cell>
                        
                    <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                            
                            <flux:menu>
                                <flux:menu.item wire:click="$dispatch('load-meeting-view-modal', { meetingId: {{ $meeting->id }} })" icon="eye" >{{ __('View') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        @else
            <div class="h-3/4 flex justify-center items-center">
                <div class="text-center">No meeting logged for this project</div>
            </div>
        @endif
