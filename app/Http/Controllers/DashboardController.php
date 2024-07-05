<?php

    namespace App\Http\Controllers;

    use App\Models\Movie;
    use App\Models\MovieUser;
    use Carbon\Carbon;

    class DashboardController extends Controller
    {
        //
        public function index()
        {
            $userId = auth()->id();
            $today = Carbon::today();

            $upcomings = Movie::select('movies.*', 'movie_user.watch_type')
                ->join('movie_user', 'movies.id', '=', 'movie_user.movie_id')
                ->where('movies.release_date', '>=', $today)
                ->where('movie_user.watch_type', '!=', Movie::Watched)
                ->where('movie_user.watch_type', '!=', Movie::Abandoned)
                ->where('movie_user.user_id', $userId)
                ->orderBy('movies.release_date', 'ASC')
                ->get();

            $episodes = Movie::select('movies.*', 'movie_user.watch_type')
                ->join('movie_user', 'movies.id', '=', 'movie_user.movie_id')
                ->where('movies.next_air_date', '>=', $today)
                ->where('movie_user.watch_type', '!=', Movie::Watched)
                ->where('movie_user.watch_type', '!=', Movie::Abandoned)
                ->where('movie_user.user_id', $userId)
                ->orderBy('movies.next_air_date', 'ASC')
                ->get();
            return view('dashboard', ['upcomings' => $upcomings, 'episodes' => $episodes]);
        }
    }
