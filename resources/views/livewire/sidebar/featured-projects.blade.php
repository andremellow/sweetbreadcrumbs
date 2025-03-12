<flux:navlist.group :heading="__('Projects')" class="grid">
    @foreach($featuredProjects as $project)
        <flux:navlist.item icon="home" :href="route('projects.dashboard', [ 'organization' => $currentOrganizationSlug ,  'project' => $project->id ])" :current="request()->routeIs('dashboard')" wire:navigate>{{ $project->name }}</flux:navlist.item>
    @endforeach
</flux:navlist.group>