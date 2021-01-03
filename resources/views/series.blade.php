@extends('layout.app')
  @section('content')
 <div class="container px-4 pt-16">
    <div class="popular-movies mt-8">
      <h2 class="tracking-wider text-orange-500 font-semibold uppercase text-lg">
        popular movies
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($popularMovies as $movie)
          <x-movie-card :movie="$movie" :genres="$genres"/>
        @endforeach
        </div>

      </div><!-- end of popular movie -->

      <div class="now-playing mt-8">
        <h2 class="tracking-wider text-orange-500 font-semibold uppercase text-lg">
          now playing
        </h2>


      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($nowPlayingMovies as $movie)
           <x-t-card :movie="$movie" :genres="$genres"/>
        @endforeach
        </div><!-- end of now playing movie -->
  </div>

  @endsection
