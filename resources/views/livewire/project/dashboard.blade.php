<section class="w-full">
    @include('partials.projects-heading')

    <x-projects.layout :$project >
        <div class="flex h-full  w-full flex-1 flex-col gap-4 ">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Last meetings') }}</flux:subheading>
                    <livewire:project.list-meetings-card :$project />
                    <livewire:meeting.meeting-view-modal/>
                </div>
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <div class="flex justify-between">
                        <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Meeska, Mooska, Taskadoer') }}</flux:subheading>
                        <flux:dropdown>
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                            
                            <flux:menu>
                                <flux:menu.item icon="pencil-square" >{{ __('Edit Task') }}</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                    <livewire:project.list-tasks-card :$project/>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>

    </x-projects.layout>
</section>
