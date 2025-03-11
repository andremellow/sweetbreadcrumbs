<div class="mb-6 w-full">
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">{{ __('Meetings') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('You don\'t need to remember everything.') }}</flux:subheading>
        </div>
        <div class="flex items-end mb-6">
            {{ $slot }}
        </div>
    </div>
    <flux:separator variant="subtle" />

    
</div>
