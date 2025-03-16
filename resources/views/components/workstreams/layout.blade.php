@props([ 'workstream' ])
<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('workstreams.dashboard', [ 'organization' => $currentOrganizationSlug, 'workstream' => $workstream->id ])" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            <flux:navlist.item :href="route('workstreams.meetings.index', [ 'organization' => $currentOrganizationSlug, 'workstream' => $workstream->id ])" wire:navigate>{{ __('Meetings') }}</flux:navlist.item>
            <flux:navlist.item :href="route('workstreams.tasks.index', [ 'organization' => $currentOrganizationSlug, 'workstream' => $workstream->id ])" wire:navigate>{{ __('Tasks') }}</flux:navlist.item>
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
