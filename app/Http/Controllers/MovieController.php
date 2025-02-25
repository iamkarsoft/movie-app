<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieUser;
use App\Services\Movies\Apis\TmdbApi;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index()
    {
        $token = 'services.tmdb.token';

        $popularMovie = TmdbApi::connect($token, 'https://api.themoviedb.org/3/movie/popular', 'results');

        $popularMovies = collect($popularMovie)->sortBy('release_date')->reverse()->toArray();

        $genres = TmdbApi::getGenres();
        $tvShow = TmdbApi::connect($token, 'https://api.themoviedb.org/3/tv/on_the_air', 'results');

        $tvShows = collect($tvShow)->sortBy('last_episode_to_air')->reverse()->toArray();
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

    public function show($id)
    {
        $token = 'services.tmdb.token';
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images')
            ->json();

        if (array_key_exists('original_title', $movie)) {
            $identifiable = $movie['original_name'] ?? $movie['title'];
        } else {
            $identifiable = $movie['original_name'] ?? $movie['name'];
        }

        if (auth()->user()) :
            $movie_db = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
                ->join('users', 'users.id', '=', 'movie_user.user_id')
                ->select('users.*', 'movies.*', 'movie_user.*')
                ->where('movies.name', $identifiable)
                ->where('movie_user.user_id', auth()->user()->id)
                ->first();
        else :

            $movie_db = Movie::query()
                ->where('movies.name', $identifiable);
        endif;

        return view('movie', compact('movie', 'movie_db'));
    }
}
