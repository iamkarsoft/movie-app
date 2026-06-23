@extends('layout.app')
@section('content')
<div class="container mx-auto px-4 pt-16 pb-16">
    <div class="mb-8">
        <h2 class="text-3xl font-semibold text-white">
            Movie results for <span class="text-orange-500">"{{ $query }}"</span>
        </h2>
        <p class="text-gray-400 mt-1">{{ count($results) }} result{{ count($results) !== 1 ? 's' : '' }} found</p>
    </div>

    @if(count($results) > 0)
        <div class="md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach($results as $movie)
                <x-movie-card :movie="$movie" :genres="$genres" />
            @endforeach
        </div>
    @else
        <div class="text-gray-400 text-lg py-16 text-center">
            No movies found for "{{ $query }}".
        </div>
    @endif
</div>
@endsection
