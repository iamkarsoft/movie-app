@extends('layout.app')
@section('content')
<div class="container mx-auto px-4 pt-12 pb-16">

    <div class="mb-10">
        <h1 class="text-3xl font-bold text-white">Movies</h1>
        <p class="text-zinc-400 mt-1">Browse popular and now playing movies</p>
    </div>

    <section class="mb-14">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-white uppercase tracking-widest">Popular</h2>
            <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($popularMovies as $movie)
                <x-movie-card :movie="$movie" :genres="$genres" />
            @endforeach
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-white uppercase tracking-widest">Now Playing</h2>
            <div class="h-px flex-1 bg-zinc-800 ml-4"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($nowPlayingMovies as $movie)
                <x-movie-card :movie="$movie" :genres="$genres" />
            @endforeach
        </div>
    </section>

</div>
@endsection
