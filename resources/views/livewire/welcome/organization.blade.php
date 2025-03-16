<flux:card class="space-y-6 min-w-sm md:min-w-md min-h-64 shadow-sm">
    <div>
        <p class="text-sm font-medium text-gray-900">Let's gets started...</p>
        <div class="mt-6" aria-hidden="true">
            <div class="overflow-hidden rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-gray-600" style="width: 0%"></div>
            </div>
            <div class="mt-3 hidden grid-cols-2 text-sm font-medium text-gray-600 sm:grid">
            <div class="text-gray-600">Organization</div>
            <div class="text-center text-gray-600">Workstream</div>
            
            </div>
        </div>
    </div>
    
    <form wire:submit="create" class="mt-6">
        <div class="space-y-6">
            <flux:input label="What is your organization name?" placeholder="Organization Name" wire:model="name" />
    
            <flux:button type="submit" variant="primary" class="w-full">Create</flux:button>
        </div>
    </form>
</flux:card>