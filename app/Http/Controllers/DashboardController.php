<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // $upcomings = MovieUser::query()
        //     ->where('release_date', '>=', Carbon::today())
        //     ->where('user_id', auth()->id())
        //     ->where('watch_type', !Movie::Watched)
        //     ->orderBy('release_date', 'ASC')
        //     ->get();

        $upcomings = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.watch_type', 'movies.release_date')
            ->where('movies.release_date', '>=', Carbon::today())
            ->where('movie_user.watch_type', ! Movie::Watched)
            ->where('movie_user.user_id', auth()->user()->id)
            ->orderBy('movies.release_date', 'ASC')
            ->get();

        // $episodes = Movie::query()
        //     ->where('next_air_date', '>=', Carbon::today())
        //     ->where('user_id', auth()->id())
        //     ->where('watch_type', !Movie::Watched)
        //     ->orderBy('next_air_date', 'ASC')
        //     ->get();

        $episodes = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.watch_type')
            ->where('movies.next_air_date', '>=', Carbon::today())
            ->where('movie_user.watch_type', ! Movie::Watched)
            ->where('movie_user.user_id', auth()->user()->id)
            ->orderBy('movies.next_air_date', 'ASC')
            ->get();

        return view('dashboard', ['upcomings' => $upcomings, 'episodes' => $episodes]);
    }
}
