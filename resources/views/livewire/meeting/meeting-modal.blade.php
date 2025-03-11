<flux:modal 
    name="meeting-form-modal"
    variant="flyout"
    :dismissible="false"
    wire:model.self="showMeetingFormModal"
    @close="onModalClose"
    class="w-full xl:w-2/3 2xl:w-1/2"
>
    <form wire:submit="save">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Meeting for {{ $this->form->project->name }}</flux:heading>
                <flux:subheading>Log It Before You Forget It—Because Meetings Deserve a Paper Trail!</flux:subheading>
            </div>

            <flux:input label="Name" placeholder="Meeing Name" wire:model="form.name" />

            <flux:editor wire:model="form.description" label="Description"  />

            <flux:date-picker wire:model="form.date" label="Meeting date" />
            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </div>
    </form>
</flux:modal>