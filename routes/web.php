<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'MovieController@index')->name('movies');
Route::get('movie/{movie}', 'MovieController@show')->name('movie.show');
Route::get('movies', 'MovieController@movies')->name('movie.list');
Route::get('tvshow/{tv}', 'TvShowController@show')->name('tv.show');
Route::get('tvshow', 'TvShowController@index')->name('tv.list');
