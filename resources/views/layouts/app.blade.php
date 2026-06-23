<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Movie App') }}</title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-svh bg-zinc-950 font-sans antialiased">

    <flux:sidebar sticky stashable class="bg-zinc-900 border-r border-zinc-800">
        <flux:sidebar.toggle class="lg:hidden" />

        <flux:sidebar.brand href="{{ route('movies') }}" name="Movie App">
            <x-slot name="logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-rose-500">
                    <path d="M3.25 4A2.25 2.25 0 0 0 1 6.25v7.5A2.25 2.25 0 0 0 3.25 16h7.5A2.25 2.25 0 0 0 13 13.75v-7.5A2.25 2.25 0 0 0 10.75 4h-7.5ZM19 4.75a.75.75 0 0 0-1.28-.53l-3 3a.75.75 0 0 0-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 0 0 1.28-.53V4.75Z" />
                </svg>
            </x-slot>
        </flux:sidebar.brand>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="{{ route('movies') }}">
                Browse
            </flux:navlist.item>
            <flux:navlist.item
                icon="squares-2x2"
                href="{{ route('dashboard') }}"
                :current="request()->routeIs('dashboard')"
            >
                Dashboard
            </flux:navlist.item>
            <flux:navlist.item
                icon="bookmark"
                href="{{ route('watchlist') }}"
                :current="request()->routeIs('watchlist')"
            >
                Watch List
            </flux:navlist.item>
        </flux:navlist>

        <flux:separator class="my-1" />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="circle-stack" href="{{ route('db-sync.index') }}" :current="request()->routeIs('db-sync.*')">
                DB Sync
            </flux:navlist.item>
        </flux:navlist>

        <flux:separator class="my-1" />

        <flux:navlist variant="outline">
            <flux:navlist.group heading="Settings">
                <flux:navlist.item icon="wrench-screwdriver" href="{{ route('settings.developer-tools') }}" :current="request()->routeIs('settings.developer-tools')">
                    Developer Tools
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        @auth
            <flux:dropdown position="top" align="start">
                <flux:profile
                    :name="Auth::user()->name"
                    :subtext="Auth::user()->email"
                    class="cursor-pointer"
                />
                <flux:menu>
                    <flux:menu.item
                        icon="film"
                        icon:trailing="arrow-top-right-on-square"
                        href="{{ route('movie.list') }}"
                        target="_blank"
                    >
                        Movies
                    </flux:menu.item>
                    <flux:menu.item
                        icon="video-camera"
                        icon:trailing="arrow-top-right-on-square"
                        href="{{ route('tv.list') }}"
                        target="_blank"
                    >
                        TV Shows
                    </flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item icon="arrow-right-start-on-rectangle" type="submit">
                            Log Out
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @endauth
    </flux:sidebar>

    <flux:header sticky class="lg:hidden bg-zinc-900 border-b border-zinc-800 px-4">
        <flux:sidebar.toggle icon="bars-3" variant="subtle" />
        <flux:spacer />
        <span class="text-sm font-semibold text-white">Movie App</span>
        <flux:spacer />
    </flux:header>

    <flux:main class="bg-zinc-950 text-white">
        <div class="max-w-7xl mx-auto">
            @if (isset($header))
                <div class="mb-6 pb-6 border-b border-zinc-800">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </flux:main>

    @livewireScriptConfig
    @fluxScripts
</body>
</html>
