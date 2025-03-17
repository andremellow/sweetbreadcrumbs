<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title> {{ config('app.name') }} </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="">
    <div class="bg-white">
            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                
                <div class="flex flex-1 justify-end">
                @auth
                    @php
                        $userService = app(App\Services\UserService::class);
                        $organization = $userService->getCurrentOrganization();
                        $link = route('welcome.organization');

                        if($organization) {
                            $link = route('dashboard', [ 'organization' => $organization->slug ]);
                        }

                    @endphp
                    <a href="{{ $link }}" class="text-sm/6 font-semibold text-gray-900">Dashboard<span aria-hidden="true">&rarr;</span></a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-gray-900">Log in<span aria-hidden="true">&rarr;</span></a>
                    @endauth
                </div>
                </nav>

            </header>

            <div class="relative isolate px-6 pt-14 lg:px-8">
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <div class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                        Beta
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">SWEET BREADCRUMBS</h1>
                    @php
                        $punchlines = [
                            "Sweet Bread Crumbs: Guiding Your Work, One Step at a Time.",
                            "Follow the Sweet Bread Crumbs to a More Organized Workflow.",
                            "No More Getting Lost—Sweet Bread Crumbs Keeps You on Track.",
                            "Sweet Bread Crumbs: Smart Paths, Seamless Productivity.",
                            "Workflows as Smooth as Butter—Follow the Sweet Bread Crumbs!"
                        ];
                    @endphp
                    <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">{{ $punchlines[array_rand($punchlines)] }}</p>
                </div>
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </div>
            </div>

    </body>
</html>
