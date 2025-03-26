<div id="role-dropdown">
    <flux:select label="Role" wire:model.live="roleId"  >
            <flux:select.option wire:key="empty" value="">Select a Role...</flux:select.option>
        @foreach($roles as $id => $name)
            <flux:select.option wire:key="{{ $id }}" value="{{ $id }}">{{ $name }}</flux:select.option>
        @endforeach
    </flux:select>
</div>