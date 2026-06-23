<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TvShowController extends Controller
{
    //
    public function index()
    {
        // tv shows

        $popularShow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/popular')
            ->json()['results'];

        $popularShow = collect($popularShow)->sortBy('first_air_date')->reverse()->toArray();

        $tvShow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/on_the_air')
            ->json()['results'];

        $tvShows = collect($tvShow)->sortBy('last_episode_to_air ')->reverse()->toArray();

        $tvGenres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        return view('series', [
            'popularShow' => $popularShow,
            'tvShows' => $tvShows,
            'tvGenres' => $tvGenres,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) > 2) {
            $response = Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/search/tv', ['query' => $query])
                ->json();
            $results = $response['results'] ?? [];
        }

        $tvGenres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'] ?? [];

        return view('search.tv', compact('results', 'query', 'tvGenres'));
    }

    public function show($id)
    {
        $tv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$id.'?append_to_response=credits,videos,images,')
            ->json();

        $identifiable = $tv['original_name'] ?? $tv['name'];

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

        return view('tvshow', compact('tv', 'movie_db'));
    }
}
