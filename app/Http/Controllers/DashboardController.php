<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $upcomings = Movie::query()
            ->where('release_date', '>=', Carbon::today())
            ->where('user_id', auth()->id())
            ->where('watch_type', Movie::Watched)
            ->orderBy('next_air_date', 'ASC')
            ->get();

        $episodes = Movie::query()
            ->where('next_air_date', '>=', Carbon::today())
            ->where('user_id', auth()->id())
            ->where('watch_type', !Movie::Watched)
            ->orderBy('next_air_date', 'ASC')
            ->get();
        return view('dashboard', ['upcomings' => $upcomings, 'episodes' => $episodes]);
    }
}
