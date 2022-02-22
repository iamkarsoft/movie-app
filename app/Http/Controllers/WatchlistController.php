<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WatchlistController extends Controller
{

    public function index(){
        return view('wishlist');
    }
}
