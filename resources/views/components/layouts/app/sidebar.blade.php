@use(App\Services\UserService)
@php
    $userService = app(UserService::class);
    $organization = $userService->getCurrentOrganization();
    $usesOrganizations = $userService->getOrganizations();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <livewire:workstream.workstream-modal />
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="{{ $organization->name }}" expandable>
                    <flux:navlist.item href="{{ route('dashboard', [ 'organization' => $organization->slug ]) }}">Dashboard</flux:navlist.item>
                </flux:navlist.group>
                <livewire:sidebar.featured-workstreams />
                
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="{{ route('workstreams.index') }}" wire:navigate>
                    {{ __('Manage Workstreams') }}
                </flux:navlist.item>

                <flux:modal.trigger name="workstream-form-modal">
                    <flux:navlist.item icon="folder-git-2">
                        {{ __('Add Workstream') }}
                    </flux:navlist.item>
                </flux:modal.trigger>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->first_name"
                    :avatar="auth()->user()->avatar ?: null"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-sm">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        @if (auth()->user()->avatar)
                                            <img src="{{ auth()->user()->avatar }}" />
                                        @else
                                            {{ auth()->user()->initials() }}
                                        @endif
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="{{ route('settings.profile') }}" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.submenu heading="Organizations">
                            <flux:menu.group heading="Organizations">
                                @foreach($usesOrganizations as $org) 
                                    <flux:navmenu.item  href="{{ route('dashboard', ['organization' => $org->slug]) }}" :checked="$org->id === $organization->id">
                                        <div class="w-full flex justify-between items-center">
                                            <div class="flex space-x-1 items-center">
                                                @if($org->id === $organization->id) 
                                                    <flux:icon.check variant="micro"/> 
                                                @endif
                                                <div>
                                                {{ $org->name }}
                                                </div>
                                            </div>
                                            @if(Feature::active('dev'))
                                                @if($org->id === $organization->id) 
                                                    <a class="ml-2" href="{{ route('organization.settings', [ 'organization' => $org->slug ]) }}">
                                                        <flux:icon.settings variant="mini"/> 
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </flux:navmenu.item>
                                @endforeach
                            </flux:menu.group>
                        </flux:menu.submenu>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :avatar="auth()->user()->avatar ?: null"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-sm">
                                    @if (auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-sm bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        <flux:toast  position="top right" />
    </body>
</html>
