<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

class TvShowController extends Controller {
	//
	public function index() {

		// tv shows

		$popularShow = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/popular')
			->json()['results'];

		$tvShows = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/on_the_air')
			->json()['results'];

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

		return view('tvshow', compact('tv'));
	}
}
