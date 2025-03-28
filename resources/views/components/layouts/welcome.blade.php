<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 flex items-center justify-center">
        <!-- Mobile User Menu -->
        
        {{ $slot }}

        @fluxScripts
        <flux:toast  position="top right" />
    </body>
</html>
