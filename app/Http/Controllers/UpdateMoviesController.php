<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdateMoviesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //




        $movies = Movie::get();



        foreach ($movies as $movie) {
            if ($movie->type == 0) {
                $movie_request = Http::withToken(config('services.tmdb.token'))
                    ->get("https://api.themoviedb.org/3/movie/{$movie->movie_id}")
                    ->json();
                // dd($movie_request);

                if (array_key_exists('release_date', $movie_request)) {
                    $movie->release_date = $movie_request['release_date'];
                }
            }

            if ($movie->type == 1) {
                $tv_request = Http::withToken(config('services.tmdb.token'))
                    ->get("https://api.themoviedb.org/3/tv/{$movie->movie_id}")
                    ->json();



                if (array_key_exists('last_episode_to_air', $tv_request)) {
                    $movie->last_air_date = $tv_request['last_episode_to_air']['air_date'];

                    if (isset($tv_request['next_episode_to_air']['air_date'])) {
                        $movie->next_air_date = $tv_request['next_episode_to_air']['air_date'];
                    } else {
                        $movie->next_air_date = Null;
                    }
                }
            }
            dump($movie->name);
            $movie->updated_at = Carbon::now();
            $movie->save();
        }

        // dd($movies);
    }
}
