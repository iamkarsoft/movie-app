<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;

class TvShowController extends Controller {
	//
	public function index() {

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
	public function show($id) {
		$tv = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=credits,videos,images,')
			->json();


          if($tv['original_name']){
            $identifiable = $tv['original_name'];
        }else{
            $identifiable = $tv['name'];
        }
          $movie_db = Movie::where('name', $identifiable)->first();

		return view('tvshow', compact('tv','movie_db'));
	}
}
