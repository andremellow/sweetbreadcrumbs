<flux:modal name="project-form-modal" variant="flyout" :dismissible="false" wire:model.self="showProjectFormModal"  @close="onModalClose">
    <form wire:submit="save">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Project</flux:heading>
                <flux:subheading>Keep on track</flux:subheading>
            </div>

            <flux:input label="Name" placeholder="Project Name" wire:model="form.name" />

            <div>
                <livewire:priority-dropdown
                    wire:model="form.priority_id"
                />
                <flux:error name="form.priority_id"/>
            </div>
            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </div>
    </form>
</flux:modal>