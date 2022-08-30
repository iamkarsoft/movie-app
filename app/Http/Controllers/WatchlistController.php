<?php

namespace App\Http\Controllers;

use App\Models\MovieUser;
use Livewire\WithPagination;

class WatchlistController extends Controller
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function index($filter = null)
    {
        if ($filter != null) {
            $watchlist = auth()->user()->movies()->latest('next_air_date')->where('watch_type', $filter)->paginate(10);
            $watchlist = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*', 'movie_user.watch_type as watch_type' )
            ->where('movie_user.user_id', auth()->user()->id)
                 ->latest('next_air_date')->where('watch_type', $filter)
            ->paginate(10);
        } else {
//            $watchlist = auth()->user()->movies()->latest('next_air_date')->paginate(10);
            $watchlist = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->where('movie_user.user_id', auth()->user()->id)
              ->latest('next_air_date')
            ->paginate(10);
        }
        ray($watchlist);

        return view('watchlist', ['watchlist' => $watchlist]);
    }
}
