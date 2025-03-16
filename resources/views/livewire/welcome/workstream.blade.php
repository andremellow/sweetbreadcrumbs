<flux:card class="space-y-6 min-w-sm md:min-w-md min-h-64 shadow-sm">
    <div>
        <p class="text-sm font-medium text-gray-900">Let's gets started...</p>
        <div class="mt-6" aria-hidden="true">
            <div class="overflow-hidden rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-gray-600" style="width: 50%"></div>
            </div>
            <div class="mt-3 hidden grid-cols-2 text-sm font-medium text-gray-600 sm:grid">
            <div class="text-gray-600">Organization</div>
            <div class="text-center text-gray-600">Workstream</div>
            
            </div>
        </div>
    </div>
    
    <form wire:submit="create" class="mt-6">
        <div class="space-y-6">
            <flux:input label="What is the first thing you wanna control?" placeholder="Workstream Name" wire:model="name" />
            
            <flux:modal.trigger name="about-workstream">
                <div class="flex items-center pb-4 -mt-4 space-x-1 cursor-pointer">
                    <flux:description>Learn more about workstreams. </flux:description>
                    <flux:icon.info variant="micro" />
                </div>
            </flux:modal.trigger>
            <flux:button type="submit" variant="primary" class="w-full">Create</flux:button>
        </div>
    </form>

    <x-about-workstream />
</flux:card>