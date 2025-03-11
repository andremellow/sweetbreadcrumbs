<section class="w-full">
    @include('partials.projects-heading')

    <x-projects.layout :$project >
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="first_name" :label="__('First Name')" type="text" required autofocus autocomplete="first_name" />

            <flux:input wire:model="last_name" :label="__('Last Name')" type="text" required autofocus autocomplete="last_name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required disabled autocomplete="email" />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

    </x-projects.layout>
</section>
