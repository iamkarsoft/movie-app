<?php

namespace App\Http\Controllers;

use App\Services\MovieDateUpdater;

class UpdateMoviesController extends Controller
{
    public function __invoke(MovieDateUpdater $updater)
    {
        return response()->json($updater->run());
    }
}
