<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movie App</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-white min-h-screen">

    @if(session()->has('message'))
        <div class="bg-green-600 text-white text-sm text-center py-2">{{ session('message') }}</div>
    @endif

    <nav class="border-b border-zinc-800 bg-gray-900 sticky top-0 z-40">
        <div class="container mx-auto flex items-center justify-between px-4 py-3 gap-4">

            {{-- Logo + Nav links --}}
            <div class="flex items-center gap-6 shrink-0">
                <a href="{{ route('movies') }}" class="font-bold text-white text-lg tracking-tight hover:text-rose-400 transition-colors">
                    Movie App
                </a>
                <div class="hidden md:flex items-center gap-5 text-sm text-zinc-400">
                    <a href="{{ route('movie.list') }}" class="hover:text-white transition-colors">Movies</a>
                    <a href="{{ route('tv.list') }}" class="hover:text-white transition-colors">TV Shows</a>
                </div>
            </div>

            {{-- Search + Profile --}}
            <div class="flex items-center gap-3">
                <livewire:search-dropdown />

                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-8 h-8 rounded-full bg-zinc-700 hover:bg-zinc-600 flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-zinc-300">
                                <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-48 bg-zinc-800 rounded-xl shadow-xl ring-1 ring-white/10 overflow-hidden z-50">
                            <div class="px-4 py-2.5 border-b border-zinc-700">
                                <p class="text-xs text-zinc-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-zinc-200 hover:bg-zinc-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-zinc-400">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('movie.list') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-zinc-200 hover:bg-zinc-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-zinc-400">
                                    <path d="M3.25 4A2.25 2.25 0 0 0 1 6.25v7.5A2.25 2.25 0 0 0 3.25 16h7.5A2.25 2.25 0 0 0 13 13.75v-7.5A2.25 2.25 0 0 0 10.75 4h-7.5ZM19 4.75a.75.75 0 0 0-1.28-.53l-3 3a.75.75 0 0 0-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 0 0 1.28-.53V4.75Z" />
                                </svg>
                                Movies
                            </a>
                            <a href="{{ route('tv.list') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-zinc-200 hover:bg-zinc-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-zinc-400">
                                    <path fill-rule="evenodd" d="M1 4.75C1 3.784 1.784 3 2.75 3h14.5c.966 0 1.75.784 1.75 1.75v10.515a1.75 1.75 0 0 1-1.75 1.75h-1.5c-.078 0-.155-.005-.23-.015H4.48c-.075.01-.152.015-.23.015h-1.5A1.75 1.75 0 0 1 1 15.265V4.75Zm16.5 7.385V11.01a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25Zm0 2.005a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .108.069.2.165.235h1.585a.25.25 0 0 0 .25-.25v-1.11Zm-15 1.11v-1.11a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.164.235H2.75a.25.25 0 0 1-.25-.25Zm2-4.24v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V11.01a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25Zm13-2.005V7.88a.25.25 0 0 0-.25-.25h-1.5a.25.25 0 0 0-.25.25v1.125c0 .138.112.25.25.25h1.5a.25.25 0 0 0 .25-.25ZM4.25 7.63a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V7.88a.25.25 0 0 1 .25-.25h1.5Zm0-3.13a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5Zm11.5 1.625a.25.25 0 0 1-.25-.25V4.75a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v1.125a.25.25 0 0 1-.25.25h-1.5Zm-9 3.125a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd" />
                                </svg>
                                TV Shows
                            </a>
                            <div class="border-t border-zinc-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-zinc-400 hover:text-white hover:bg-zinc-700 transition-colors text-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd" />
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('login') }}" class="text-zinc-400 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="border-t border-zinc-800 mt-8">
        <div class="container mx-auto text-sm px-4 py-6 text-zinc-500">
            Powered by <a target="_blank" href="https://www.themoviedb.org/documentation/api" class="underline hover:text-zinc-300 transition-colors">TMDb API</a>
        </div>
    </footer>

    @livewireScriptConfig
</body>
</html>
