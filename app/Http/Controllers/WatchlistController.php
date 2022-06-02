<?php

namespace App\Http\Controllers;

use Livewire\WithPagination;

class WatchlistController extends Controller
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function index($filter = null)
    {
        if ($filter != null) {
            $watchlist = auth()->user()->movies()->latest('next_air_date')->where('watch_type', $filter)->paginate(10);
        } else {
            $watchlist = auth()->user()->movies()->latest('next_air_date')->paginate(10);
        }

        return view('watchlist', ['watchlist' => $watchlist]);
    }
}
