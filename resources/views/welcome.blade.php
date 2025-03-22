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

                        if(!$organization) {
                            $organization = $userService->getOrganizations()->first();
                        }



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
        <div class="flex justify-center pb-12">
        <nav aria-label="Progress">
            <ol role="list" class="overflow-hidden">
                <li class="relative pb-10">
                <div class="absolute top-4 left-4 mt-0.5 -ml-px h-full w-0.5 bg-red-300" aria-hidden="true"></div>
                <!-- Complete Step -->
                <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center">
                    <span class="relative z-10 flex size-8 items-center justify-center rounded-full bg-red-300 group-hover:bg-red-300">
                        <svg class="size-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                    <span class="text-sm font-medium">Launch V0.0.1</span>
                    <span class="text-sm text-gray-500">Beta Release.</span>
                    </span>
                </a>
                </li>
                <li class="relative pb-10">
                <div class="absolute top-4 left-4 mt-0.5 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></div>
                <!-- Current Step -->
                <a href="#" class="group relative flex items-start" aria-current="step">
                    <span class="flex h-9 items-center" aria-hidden="true">
                    <span class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-red-300 bg-white">
                        <span class="size-2.5 rounded-full bg-red-300"></span>
                    </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                    <span class="text-sm font-medium text-red-300">Permissions & Security @ {{ Carbon\Carbon::create(2025,04,01)->toFormattedDayDateString() }}</span>
                    <span class="text-sm text-gray-500">Invite user to organization.</span>
                    <span class="text-sm text-gray-500">User's permissions.</span>
                    <span class="text-sm text-gray-500">Workstream & Task access control between team members</span>
                    <span class="text-sm text-gray-500">New logo</span>
                    </span>
                </a>
                </li>
                <li class="relative pb-10">
                <div class="absolute top-4 left-4 mt-0.5 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></div>
                <!-- Upcoming Step -->
                <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center" aria-hidden="true">
                    <span class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                        <span class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                    </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                    <span class="text-sm font-medium text-red-300">Risks & Notes  @ {{ Carbon\Carbon::create(2025,04,15)->toFormattedDayDateString() }}</span>
                    <span class="text-sm text-gray-500">Ability to create and magene risks.</span>
                    <span class="text-sm text-gray-500">Ability to create and magene notes.</span>
                    <span class="text-sm text-gray-500">Dashboard improvments.</span>
                    <span class="text-sm text-gray-500">Daily digest emails.</span>
                    </span>
                </a>
                </li>
                <li class="relative pb-10">
                <div class="absolute top-4 left-4 mt-0.5 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></div>
                <!-- Upcoming Step -->
                <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center" aria-hidden="true">
                    <span class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                        <span class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                    </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                    <span class="text-sm font-medium text-red-300">Dashboard enhancement @ {{ Carbon\Carbon::create(2025,05,1)->toFormattedDayDateString() }}</span>
                    <span class="text-sm text-gray-500">User's dashboard across all organizations.</span>
                    <span class="text-sm text-gray-500">Notes specific dashboard.</span>
                    <span class="text-sm text-gray-500">Task specific dashboard.</span>
                    </span>
                </a>
                </li>
                <li class="relative">
                <!-- Upcoming Step -->
                <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center" aria-hidden="true">
                    <span class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                        <span class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                    </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                    <span class="text-sm font-medium text-green-500 blink">Go Live @ {{ Carbon\Carbon::create(2025, 06, 1)->toFormattedDayDateString() }}</span>
                    <span class="text-sm text-gray-500">Official support start.</span>
                    <span class="text-sm text-gray-500">It will still be free for a while (Not for long).</span>
                    <span class="text-sm text-gray-500">We gonna start working on integrations (Jira, Microsoft, etc).</span>
                    </span>
                </a>
                </li>
            </ol>
            </nav>
        </div>
    </body>
    <style>
  @keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
  }

  .blink {
    animation: blink 1s infinite; /* 1 second duration, infinitely blinking */
  }
</style>
</html>
