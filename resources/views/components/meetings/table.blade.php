@props([ 'meetings', 'sortBy', 'sortDirection' ])
<div>
    
    <flux:table :paginate="$meetings" class="w-full table-auto mt-5">
        <flux:table.columns>
            <flux:table.column class="w-1/8">ID</flux:table.column>
            <flux:table.column class="w-5/8" sortable :sorted="$sortBy === 'name'" :direction="$sortDirection->value" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
            <flux:table.column class="w-1/8" sortable :sorted="$sortBy === 'date'" :direction="$sortDirection->value" wire:click="sort('date')" class="hidden sm:table-cell">{{ __('Date') }}</flux:table.column>
            <flux:table.column class="w-1/8" />
        </flux:table.columns>
        <flux:table.rows>
            
            @foreach ($meetings as $meeting)
            <flux:table.row :key="$meeting->id" >
                
                <flux:table.cell class="flex items-center gap-3">
                    {{ $meeting->id }}
                </flux:table.cell>
                
                <flux:table.cell variant="strong" class="whitespace-nowrap">
                    <div>{{ $meeting->name }}</div>
                </flux:table.cell>
                <flux:table.cell class="hidden sm:table-cell">{{ $meeting->date->format('m/d/Y') }}</flux:table.cell>
                <flux:table.cell>
                    
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                        
                        <flux:menu>
                            <flux:menu.item wire:click="$dispatch('{{ App\Enums\EventEnum::LOAD_MEETING_FORM_MODAL }}', { meetingId: {{ $meeting->id }} })" icon="pencil-square" >{{ __('Edit Meeting') }}</flux:menu.item>
                            <flux:menu.item 
                            wire:click="delete({{ $meeting->id }})"
                            wire:confirm="Are you sure you want to delete this meeting?"
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