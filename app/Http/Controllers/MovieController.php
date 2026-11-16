<?php

namespace App\Http\Controllers;

use App\Services\Movies\Apis\TmdbApi;
use App\Services\TmdbTitleResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index()
    {
        $token = 'services.tmdb.token';

        $popularMovie = TmdbApi::connect($token, 'https://api.themoviedb.org/3/movie/popular');

        $popularMovies = collect($popularMovie['results'])->sortBy('release_date')->reverse()->toArray();

        $genres = TmdbApi::getGenres();
        $tvShow = TmdbApi::connect($token, 'https://api.themoviedb.org/3/tv/on_the_air');

        $tvShows = collect($tvShow['results'])->sortBy('last_episode_to_air')->reverse()->toArray();
        $tvGenres = TmdbApi::connect($token, 'https://api.themoviedb.org/3/genre/tv/list', 'genres');

        $upcomingMovies = TmdbApi::connect($token, 'https://api.themoviedb.org/3/movie/upcoming', 'results');

        return view('index', [
            'popularMovies' => $popularMovies,
            'upcomingMovies' => $upcomingMovies,
            'genres' => $genres,
            'tvShows' => $tvShows,
            'tvGenres' => $tvGenres,
        ]);
    }

    public function movies()
    {
        // movies

        $popularMovie = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')
            ->json()['results'];

        $popularMovies = collect($popularMovie)->sortBy('release_date')->reverse()->toArray();
        $genresArray = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

        $nowPlayingMovie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];

        $nowPlayingMovies = collect($nowPlayingMovie)->sortBy('vote_average')->reverse()->toArray();

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

        return view('movies', [
            'popularMovies' => $popularMovies,
            'nowPlayingMovies' => $nowPlayingMovies,
            'genres' => $genres,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) > 2) {
            $response = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/search/movie', ['query' => $query])
                ->json();
            $results = $response['results'] ?? [];
        }

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'] ?? [];

        return view('search.movies', compact('results', 'query', 'genres'));
    }

    public function show($id, TmdbTitleResolver $resolver)
    {
        $token = 'services.tmdb.token';
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images')
            ->json();

        // Match on TMDB id (not title), so renamed / translated titles still find the user's entry.
        $movie_db = $resolver->tracked($movie, auth()->id());

        return view('movie', compact('movie', 'movie_db'));
    }
}
