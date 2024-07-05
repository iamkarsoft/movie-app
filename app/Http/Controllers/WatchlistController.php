<?php

namespace App\Http\Controllers;

use App\Models\MovieUser;
use Livewire\WithPagination;

class WatchlistController extends Controller
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function index($filter = 'watching')
    {

        $watchlist = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*', 'movie_user.watch_type as watch_type', 'movies.movie_id as movie_id')
            ->where('movie_user.user_id', auth()->user()->id)
            ->latest('next_air_date')->where('watch_type', $filter)
            ->orderBy('next_air_date', 'desc')
            ->paginate(10);


        return view('watchlist', ['watchlist' => $watchlist]);
    }
}
