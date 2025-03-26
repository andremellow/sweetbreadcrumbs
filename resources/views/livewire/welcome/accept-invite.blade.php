<flux:card class="space-y-6 min-w-sm md:min-w-md shadow-sm mx-3 sm:mx-0">
    @if($inviteBelongstoAuthenticatedUser)
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
    @else
    <flux:text size="lg">@lang('Sorry, this invite does not belong to you.')</flux:text>
    <flux:text size="lg">@lang('Please make sure you log in with the email you received the invite on.')</flux:text>
    

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <flux:button as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                {{ __('Log Out') }}
            </flux:button>
        </form>
    @endif
</flux:card>