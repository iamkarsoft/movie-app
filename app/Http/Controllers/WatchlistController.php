<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{

    public function index(){
        $watchlist = auth()->user()->movies()->latest()->paginate(10);
//        $watchlist = Movie::with('users')->get();

        return view('watchlist',['watchlist'=>$watchlist]);
    }
}
