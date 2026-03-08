<?php

namespace App\Livewire;

use App\Services\Movies\Apis\TmdbApi;
use Livewire\Component;

class Movie extends Component
{
    public $token = 'services.tmdb.token';

    public $popularMovies;

    public $upcomingMovies;

    public $genres;

    public $tvShows;

    public $tvShow;

    public $tvGenres;

    public function mount()
    {
        $this->getMovieDetails();
    }

    public function getMovieDetails()
    {
        $this->popularMovie = TmdbApi::connect($this->token, 'https://api.themoviedb.org/3/movie/popular');

        $this->popularMovies = collect($this->popularMovie['results'])->sortBy('release_date')->reverse()->toArray();

        $this->genres = TmdbApi::getGenres();
        $this->tvShow = TmdbApi::connect($this->token, 'https://api.themoviedb.org/3/tv/on_the_air');

        $this->tvShows = collect($this->tvShow['results'])->sortBy('last_episode_to_air')->reverse()->toArray();
        $this->tvGenres = TmdbApi::connect($this->token, 'https://api.themoviedb.org/3/genre/tv/list', 'genres');

        $this->upcomingMovies = TmdbApi::connect($this->token, 'https://api.themoviedb.org/3/movie/upcoming', 'results');
    }

    public function render()
    {
        return view('livewire.index')->extends('layout.app')->section('content');
    }
}
