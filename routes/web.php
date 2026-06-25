<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevToolsController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\UpdateMoviesController;
use App\Http\Controllers\WatchlistController;
use App\Livewire\Index;
use App\Livewire\Watchlist;
use Illuminate\Support\Facades\Route;

Route::get('/', Index::class)->name('movies');
Route::get('movie/{movie}', [MovieController::class, 'show'])->name('movie.show');
Route::get('movies', [MovieController::class, 'movies'])->name('movie.list');
Route::get('tvshow/{tv}', [TvShowController::class, 'show'])->name('tv.show');
Route::get('tvshow', [TvShowController::class, 'index'])->name('tv.list');
Route::get('search/movies', [MovieController::class, 'search'])->name('search.movies');
Route::get('search/tv', [TvShowController::class, 'search'])->name('search.tv');

// WatchListing routes
Route::get('/watchlist/{filter?}', [WatchlistController::class, 'index'])->name('watchlist');

Route::post('/watchlist/add', [Watchlist::class])->name('watchlist.add');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/settings/developer-tools', fn () => view('settings.developer-tools'))->middleware(['auth'])->name('settings.developer-tools');

Route::middleware(['auth'])->prefix('settings/dev-tools')->name('dev-tools.')->group(function () {
    Route::get('queue/status', [DevToolsController::class, 'status'])->name('queue.status');
    Route::post('queue/start', [DevToolsController::class, 'start'])->name('queue.start');
    Route::post('queue/stop', [DevToolsController::class, 'stop'])->name('queue.stop');
    Route::post('queue/clear-log', [DevToolsController::class, 'clearLog'])->name('queue.clear-log');
    Route::post('queue/clear', [DevToolsController::class, 'clearQueue'])->name('queue.clear');
    Route::delete('queue/job/{id}', [DevToolsController::class, 'cancelJob'])->name('queue.cancel-job');
});

// Update database
Route::get('/update/movies', UpdateMoviesController::class)->name('movies.update');

require __DIR__.'/auth.php';
