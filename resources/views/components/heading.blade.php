@props(['heading', 'subheading'])
<div class="mb-6 w-full">
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">{{ $heading }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ $subheading }}</flux:subheading>
        </div>
        <div class="flex items-end mb-6">
            {{ $slot }}
        </div>
    </div>
    <flux:separator variant="subtle" />
</div>
