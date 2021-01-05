@extends('layout.app')
@section('content')

<div class="container px-4 pt-16">

        {{-- on air --}}
    <div class="popular-show mt-8">
        <h2 class="text-4xl tracking-wider text-orange-500 font-semibold uppercase text-lg">
         Popular Shows
        </h2>


      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($popularShow as $tv)
           <x-show-card :tv="$tv" :tvGenres="$tvGenres"/>
        @endforeach
      </div>
    </div><!-- end of series-->

    {{-- on air --}}
    <div class="series mt-8">
        <h2 class="text-4xl tracking-wider text-orange-500 font-semibold uppercase text-lg">
         On Air Series
        </h2>


      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        @foreach($tvShows as $tv)
           <x-show-card :tv="$tv" :tvGenres="$tvGenres"/>
        @endforeach
      </div>
    </div><!-- end of series-->
</div>

  @endsection