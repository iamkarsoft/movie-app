<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movie App</title>
    <!-- Fonts -->
    {{-- <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <livewire:styles>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
                defer></script>
        </head>
<body class="font-sans bg-gray-900 text-white">
<nav class="border-b border-gray-800">
    <div class="container mx-auto md:flex justify-between px-4 py-6">
        <ul class="md:flex items-center">
            <li class="ml-6 lg:ml-0 font-extrabold my-4"><a href="{{route('movies')}}">Movie App</a>
            </li>
            <li class="ml-6 my-4"><a href="{{route('movie.list')}}">Movies</a></li>
            <li class="ml-6 my-4"><a href="{{route('tv.list')}}">TV Shows</a></li>
            {{-- <li class="ml-6"><a href="">Actors</a></li> --}}
        </ul>
        <div class="flex items-center">
            <livewire:search-dropdown>
                <div class="ml-4 hidden md:flex">
                    @auth
                        <div x-data="{show: false}" class="relative">
                            <button @click="show=!show">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor" class="rounded-full w-8 h-8 mt-6">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="show" class="absolute lg:w-48 bg-gray-400 p-2 right-0.5">
                                <x-dropdown-link :href="route('dashboard')">

                                    {{ __('Dashboard') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>

                    @else

                        <div class="flex">

                            <a href="{{route('login')}}" class="mx-2">Login</a>
                            <a href="{{route('register')}}" class="mx-2">register</a>
                        </div>

                    @endif
                </div>

        </div>
    </div>
</nav>
@yield('content')

<footer class="border border-t border-gray-800 mt-4">
    <div class="container mx-auto text-sm px-4 py-6">
        Powered by <a href="https://www.themoviedb.org/documentation/api"
                      class="underline hover:text-gray-300">TMDb API</a>
    </div>
    <livewire:scripts>
</body>
</html>
