<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class MovieController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		// movies

		$popularMovie = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')
			->json()['results'];
		$popularMovies = collect($popularMovie)->sortBy('release_date')->reverse()->toArray();
		$genresArray = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')
			->json()['genres'];



		// $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
		//  ->get('https://api.themoviedb.org/3/movie/now_playing')
		//  ->json()['results'];

		$genres = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/genre/movie/list')
			->json()['genres'];

		// tv shows
		$tvShow = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/on_the_air')
			->json()['results'];

		$tvShows = collect($tvShow)->sortBy('last_episode_to_air')->reverse()->toArray();

		$tvGenres = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/genre/tv/list')
			->json()['genres'];

        // upcoming movies

        $upcomingMovies = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/upcoming')->json()['results'];



		return view('index', [
			'popularMovies' => $popularMovies,
			// 'nowPlayingMovies' => $nowPlayingMovies,
            'upcomingMovies'=>$upcomingMovies,
			'genres' => $genres,
			'tvShows' => $tvShows,
			'tvGenres' => $tvGenres,
		]);
	}

	public function movies() {
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 */
	public function show($id) {
		$movie = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/movie/' . $id . '?append_to_response=credits,videos,images')
			->json();
         if(array_key_exists('original_title', $movie)) {
            if ($movie['original_title']) {
                $identifiable = $movie['original_title'];
            } else {
                $identifiable = $movie['title'];
            }
        }else{
            if ($movie['original_name']) {
                $identifiable = $movie['original_name'];
            } else {
                $identifiable = $movie['name'];
            }
        }
        $movie_db = Movie::where('name', $identifiable)->orWhere('movie_id',$movie['id'])->first();

		return view('movie', compact('movie','movie_db'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}
