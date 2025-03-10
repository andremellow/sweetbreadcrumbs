<flux:navlist.group :heading="__('Projects')" class="grid">
    @foreach($featuredProjects as $project)
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ $project->name }}</flux:navlist.item>
    @endforeach
</flux:navlist.group>