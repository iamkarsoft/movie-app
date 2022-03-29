<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{

    public function index(){
        $watchlist = Movie::where('user_id',auth()->id())->get();

        return view('watchlist',['watchlist'=>$watchlist]);
    }
}
