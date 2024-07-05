<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Http\Request;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Watchlist extends Component
{
    use WireToast;

    public Movie $movie;

    public $watchItem;

    public $movie_db;

    protected $listeners = ['watchItem' => 'store', 'movie_db' => 'destroy'];

    public function mount()
    {


        if (array_key_exists('first_air_date', $this->watchItem)) {
            $item = $this->watchItem['original_name'];
        } else {
            $item = $this->watchItem['title'];
        }
        $movie = Movie::where('name', $item)->first();

        if ($movie && blank($movie->movie_id)) {
            $movie->update([
                'movie_id'=> $this->watchItem['id'],
            ]);
        }
    }

    public function store(Request $request)
    {
        // check if user has watch listed this movie / series
        if (array_key_exists('first_air_date', $this->watchItem)) {
            $item = $this->watchItem['original_name'];
        } else {
            $item = $this->watchItem['title'];
        }

        $movie = Movie::where('name', $item)->first();
        $user_movie = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->where('movies.name', $item)
            ->where('movie_user.user_id', auth()->user()->id)
            ->first();

        if ($user_movie) {
            //            session()->flash('message', 'Already on your watch list');
            toast()
                ->info('Already on your watch list...', 'Notification')
                ->push();

            return;
        }

        if (! $movie) {
            $watchlist = new Movie();

            if (array_key_exists('first_air_date', $this->watchItem)) {
                $watchlist->type = Movie::Series;

                if ($this->watchItem['name']) {
                    $watchlist->name = $this->watchItem['name'];
                } else {
                    $watchlist->name = $this->watchItem['original_name'];
                }
                $watchlist->release_date = $this->watchItem['first_air_date'];

                if (blank($this->watchItem['next_episode_to_air'])) {
                    $watchlist->next_air_date = null;
                } else {
                    $watchlist->next_air_date = $this->watchItem['next_episode_to_air']['air_date'];
                }

                if (blank($this->watchItem['last_episode_to_air']) || blank($this->watchItem['last_air_date'])) {
                    $watchlist->last_air_date = null;
                } else {
                    $watchlist->last_air_date = $this->watchItem['last_episode_to_air']['air_date'];
                }
            } else {
                $watchlist->release_date = $this->watchItem['release_date'];
                $watchlist->type = Movie::Movies;
                $watchlist->name = $this->watchItem['title'];
                $watchlist->movie_id = $this->watchItem['id'];
                $watchlist->next_air_date = null;
                $watchlist->last_air_date = null;
            }
            $watchlist->save();
        } else {
            $watchlist = $movie;
        }
        toast()
            ->success('Added to watch list', 'Notification')
            ->push();

        $request->user()->movies()->syncWithoutDetaching($watchlist);

        return redirect(request()->header('Referer'));
    }

    public function destroy(Request $request)
    {
        // $movie = Movie::where('name', $this->movie_db->name)
        //     ->orWhere('movie_id', $this->movie_db->movie_id)
        //     ->first();

        $movie = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->where('movie_user.movie_id', $this->movie_db->movie_id)
            ->where('movie_user.user_id', auth()->user()->id)
            ->first();

        // dd([$this->movie_db, $movie]);

        $movie->delete();

        toast()
            ->success('Removed to watch list', 'Notification')
            ->push();
        // } else {

        //     toast()
        //         ->danger('Movie not your watchlist', 'Notification')
        //         ->push();
        // }
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.watchlist');
    }
}
