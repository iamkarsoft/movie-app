<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $upcomings = Movie::where('release_date','>=',Carbon::today())->get();
        $episodes= Movie::where('next_air_date','>=',Carbon::today())->get();
            return view('dashboard',['upcomings'=>$upcomings,'episodes'=>$episodes]);
    }
}
