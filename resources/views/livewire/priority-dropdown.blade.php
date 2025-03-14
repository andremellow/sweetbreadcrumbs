<div id="priority-dropdown">
    <flux:select label="Priority" wire:model.live="priorityId"  >
            <flux:select.option wire:key="empty" value="">Select a priority...</flux:select.option>
        @foreach($priorities as $id => $name)
            <flux:select.option wire:key="{{ $id }}" value="{{ $id }}">{{ $name }}</flux:select.option>
        @endforeach
    </flux:select>
</div>