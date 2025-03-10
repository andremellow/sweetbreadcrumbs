<flux:modal name="create-project" variant="flyout" :dismissible="false" wire:model.self="showCreateProjectModal">
    <form wire:submit="add">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Update profile</flux:heading>
            <flux:subheading>Make changes to your personal details.</flux:subheading>
        </div>

        <flux:input label="Name" placeholder="Project Name" wire:model="name" />

        <div>
            <livewire:priority-dropdown :priorityId="$priority_id" eventName="onPriorityDropdownSelectedForCreateProject" />
            <flux:error name="priority_id"/>
        </div>
        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary">Save</flux:button>
        </div>
    </div>
    </form>
</flux:modal>