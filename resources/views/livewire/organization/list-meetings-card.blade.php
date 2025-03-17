@props([ 'meetings'])
<div>
    @if(count($meetings) > 0)
        <div>
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
                            <div class="flex flex-col gap-1">
                                <div>{{ $meeting->name }}</div>
                                <div>
                                    <flux:button 
                                        href="{{ route('workstreams.dashboard', [ 'workstream' => $meeting->workstream ]) }}"
                                        size="xs"
                                        variant="primary"
                                        wire:navigation
                                    >{{ $meeting->workstream->name }}</flux:button>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="hidden sm:table-cell">{{ $meeting->date->format('m/d/Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button 
                                type="button"
                                wire:click="$dispatch('load-meeting-view-modal', { meetingId: {{ $meeting->id }} })"
                                size="xs"
                                icon="eye"
                                variant="ghost"
                                class="cursor-pointer"
                            />    
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <livewire:meeting.meeting-view-modal/>
        </div>
        @else
            <div class="h-3/4 flex justify-center items-center">
                <div class="text-center">No meeting logged for this workstream</div>
            </div>
        @endif
</div>