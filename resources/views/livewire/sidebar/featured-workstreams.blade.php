<flux:navlist.group :heading="__('Workstreams')" expandable>
    @foreach($featuredWorkstreams as $workstream)
        <flux:navlist.item 
            class="text-wrap"
            :href="route('workstreams.dashboard', [ 'organization' => $currentOrganizationSlug ,  'workstream' => $workstream->id ])"
            :current="url()->current() === route('workstreams.dashboard', ['organization' => $currentOrganizationSlug, 'workstream' => $workstream->id])" 
            wire:navigate>
                <div class="w-48 text-wrap break-words">
                    {{ $workstream->name }}
                </div>
        </flux:navlist.item>
    @endforeach
</flux:navlist.group>

