<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

class TvShowController extends Controller {
	//

	public function show($id) {
		$tv = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=credits,videos,images,')
			->json();

		return view('tvshow', compact('tv'));
	}
}
