<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\UpdateMoviesController;
use App\Http\Controllers\WatchlistController;
use App\Livewire\Watchlist;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index'])->name('movies');
Route::get('movie/{movie}', [MovieController::class, 'show'])->name('movie.show');
Route::get('movies', [MovieController::class, 'movies'])->name('movie.list');
Route::get('tvshow/{tv}', [TvShowController::class, 'show'])->name('tv.show');
Route::get('tvshow', [TvShowController::class, 'index'])->name('tv.list');

// WatchListing routes
Route::get('/watchlist/{filter?}', [WatchlistController::class, 'index'])->name('watchlist');

Route::post('/watchlist/add', [Watchlist::class])->name('watchlist.add');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Update database
Route::get('/update/movies', UpdateMoviesController::class)->name('movies.update');

require __DIR__.'/auth.php';
