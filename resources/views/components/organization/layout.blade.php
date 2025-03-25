@props(['organization'])
<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item href="{{ route('organization.settings', [ 'organization' => $organization->slug ]) }}" wire:navigate>{{ __('Settings') }}</flux:navlist.item>
            @if(Feature::active('dev'))
            <flux:navlist.item href="{{ route('organization.invite', [ 'organization' => $organization->slug ]) }}" wire:navigate>{{ __('Invites') }}</flux:navlist.item>
            @endif
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{ $slot }}
        </div>
    </div>
</div>
