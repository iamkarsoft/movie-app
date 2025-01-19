<?php

	namespace App\Services\Movies\Apis;

    use App\Contracts\MovieApiConnection;
    use Http;

    class TmdbApi implements MovieApiConnection
	{

         public static function connect(string $token, string $url, string $type){
            return Http::withToken(config($token))->get($url)
            ->json()[$type];
        }



        public static function getGenres()
        {
             return Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];
        }
	}
