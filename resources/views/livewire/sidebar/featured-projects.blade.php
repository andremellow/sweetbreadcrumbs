<flux:navlist.group :heading="__('Projects')" expandable>
    @foreach($featuredProjects as $project)
        <flux:navlist.item 
            class="text-wrap"
            :href="route('projects.dashboard', [ 'organization' => $currentOrganizationSlug ,  'project' => $project->id ])"
            :current="url()->current() === route('projects.dashboard', ['organization' => $currentOrganizationSlug, 'project' => $project->id])" 
            wire:navigate>
                {{ $project->name }}
        </flux:navlist.item>
    @endforeach
</flux:navlist.group>

