<?php

namespace App\Http\Controllers;

use App\Services\TmdbTitleResolver;
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

    public function show($id, TmdbTitleResolver $resolver)
    {
        $tv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$id.'?append_to_response=credits,videos,images,')
            ->json();

        // Match on TMDB id (not title), so renamed / translated titles still find the user's entry.
        $movie_db = $resolver->tracked($tv, auth()->id());

        return view('tvshow', compact('tv', 'movie_db'));
    }
}
