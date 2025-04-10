<section class="w-full">
    <x-heading heading="{{ __('Workstreams') }}" subheading="{!! __('All you need from your workstream together.')  !!}" >
        <flux:modal.trigger name="member-access-modal" >
            <livewire:access.access-avatars :accessible="$workstream" />
        </flux:modal.trigger>
    </x-heading>


    <x-workstreams.layout :$workstream >
        <div class="flex h-full  w-full flex-1 flex-col gap-4 ">
            <div class="grid auto-rows-min gap-4 grid-cols-1 2xl:grid-cols-2">
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Last meetings') }}</flux:subheading>
                    <livewire:workstream.list-meetings-card :$workstream />
                </div>
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                    <flux:subheading size="lg" class="mb-6 text-bold">{{ __('Meeska, Mooska, Taskadoer') }}</flux:subheading>
                    <livewire:workstream.list-tasks-card :$workstream/>
                </div>
            </div>
        </div>
        <livewire:access.member-access-modal :accessible="$workstream" />
    </x-workstreams.layout>
</section>
