<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-7 fill-current text-white dark:text-black" />
        </div>
        
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="{{ route('dashboard', [ 'organization' => app(App\Services\UserService::class)->getCurrentOrganization()->slug ])  }}" current>Dashboard</flux:navbar.item>
        </flux:navbar>


        
    </flux:header>

    <flux:sidebar stashable sticky class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-7 fill-current text-white dark:text-black" />
        </div>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="{{ route('dashboard', [ 'organization' => app(App\Services\UserService::class)->getCurrentOrganization()->slug ])  }}" current>Dashboard</flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>
        {{ $slot }}
    @fluxScripts
    <flux:toast  position="top right" />
</body>
</html>
