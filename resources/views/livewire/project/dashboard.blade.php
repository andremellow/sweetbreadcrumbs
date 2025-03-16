<section class="w-full">
    @include('partials.projects-heading')

    <x-projects.layout :$project >
        <div class="flex h-full  w-full flex-1 flex-col gap-4 ">
            <div class="grid auto-rows-min gap-4 grid-cols-1 2xl:grid-cols-2">
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Last meetings') }}</flux:subheading>
                    <livewire:project.list-meetings-card :$project />
                </div>
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Meeska, Mooska, Taskadoer') }}</flux:subheading>
                    <livewire:project.list-tasks-card :$project/>
                </div>
            </div>
            <!-- <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div> -->
        </div>

    </x-projects.layout>
</section>
