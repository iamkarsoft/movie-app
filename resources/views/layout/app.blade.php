<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movie App</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <livewire:styles>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="font-sans bg-gray-900 text-white">
    <nav class="border-b border-gray-800">
        <div class="container mx-auto flex justify-between px-4 py-6">
            <ul class="flex items-center">
                <li class="ml-16"><a href="{{route('movies')}}">Movie App</a></li>
                <li class="ml-6"><a href="{{route('movies')}}">Movies</a></li>
                <li class="ml-6"><a href="">TV Shows</a></li>
                <li class="ml-6"><a href="">Actors</a></li>
            </ul>
            <div class="flex items-center">
                <livewire:search-dropdown>
                <div class="ml-4">
                    <a href="">
                        <img src="" alt="" class="rounded-full w-8 h-8">
                    </a>
                </div>

            </div>
        </div>
    </nav>
        @yield('content')
<livewire:scripts>
</body>
</html>
