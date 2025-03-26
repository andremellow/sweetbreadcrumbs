<flux:card class="space-y-6 min-w-sm md:min-w-md shadow-sm">
    <div>
            <flux:text size="lg">Welcome</flux:text>
            <div class="flex mt-5">
                <flux:text variant="strong">{{ $inviterName }}</flux:text>
                <flux:text>&nbsp;invited you to join</flux:text>
                <flux:text variant="strong">&nbsp;{{ $organizationName }}</flux:text>
                <flux:text>&nbsp;team's.</flux:text>
            </div>
    </div>
    
    <form wire:submit="accept" class="mt-6">
        <div class="space-y-6">
            @if($showForm)
            <flux:input label="First Name *" placeholder="First name" wire:model="first_name" />
            <flux:input label="Last Name" placeholder="Last name" wire:model="last_name" />
            @endif
            <flux:button type="submit" variant="primary" class="w-full">Accept</flux:button>
        </div>
    </form>
</flux:card>