@extends('layout.app')
  @section('content')
 <div class="container  mx-auto px-4 pt-16 pb-16">
    <div class="popular-movies mt-8 w-full lg:mx-auto">
      <h2 class=" text-4xl tracking-wider text-orange-500 font-semibold uppercase text-lg">
        Popular Movies
      </h2>

      <div class="md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($popularMovies as $movie)
          <x-movie-card :movie="$movie" :genres="$genres"/>
        @endforeach
        </div>

    </div><!-- end of popular movie -->


    <hr class="w-full h-2 bg-white my-10">
      {{-- Upcoming Movies --}}
    <div class="popular-movies mt-8 w-full lg:mx-auto">
      <h2 class="text-4xl tracking-wider text-orange-500 font-semibold uppercase text-lg">
          Upcoming Movies
      </h2>

      <div class="md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($upcomingMovies as $movie)
            @if($movie['release_date'] > \Carbon\Carbon::now())
          <x-movie-card :movie="$movie" :genres="$genres"/>
            @endif
        @endforeach
        </div>

    </div>
      {{-- end of upcomin movies --}}


      <hr class="w-full h-2 bg-white my-10">
    <div class="now-playing mt-8 w-full lg:mx-auto">
        <h2 class="text-4xl tracking-wider text-orange-500 font-semibold uppercase text-lg">
         Series
        </h2>


      <div class="md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($tvShows as $tv)
            <x-tv-card :tv="$tv" :tvGenres="$tvGenres" />
        @endforeach
      </div>
    </div><!-- end of now playing movie -->

{{--  --}}
</div>
  @endsection
