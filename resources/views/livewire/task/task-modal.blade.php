<flux:modal 
    name="task-form-modal"
    variant="flyout"
    :dismissible="false"
    wire:model.self="showTaskFormModal"
    @close="onModalClose"
>
    <form wire:submit="save">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Task for {{ $this->form->workstream->name }}</flux:heading>
                <flux:subheading>Small Steps, Big Wins—Let’s Tackle It!</flux:subheading>
            </div>

            <flux:input label="Name" placeholder="Task Name" wire:model="form.name" dusk="form.name" />

            <flux:editor wire:model="form.description" label="Description"  />

            <div>
                <livewire:priority-dropdown wire:model="form.priority_id" key="priority-dropdown" />
                <flux:error name="form.priority_id"/>
            </div>

            <flux:date-picker wire:model="form.due_date" label="Due date" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" >Save</flux:button>
            </div>
        </div>
    </form>
</flux:modal>