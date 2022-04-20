<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class WatchlistController extends Controller
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function index()
    {
        $watchlist = auth()->user()->movies()->latest()->paginate(2);
        //        $watchlist = Movie::with('users')->get();

        return view('watchlist', ['watchlist' => $watchlist]);
    }
}
