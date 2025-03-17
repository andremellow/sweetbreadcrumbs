<flux:card class="space-y-6 min-w-sm md:min-w-md min-h-64 shadow-sm">
    <div>
        <flux:label>Let's gets started...</flux:label>
        <div class="mt-6" aria-hidden="true">
            <div class="overflow-hidden rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-gray-600" style="width: 33%"></div>
            </div>
            <div class="mt-3 hidden grid-cols-3 text-sm font-medium text-gray-600 sm:grid">
            <flux:label>You</flux:label>
            <flux:label class="text-center">Organization</flux:label>
            <flux:label class="text-right">Workstream</flux:label>
            
            
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