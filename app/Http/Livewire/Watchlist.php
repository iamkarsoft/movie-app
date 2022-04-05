<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;

class Watchlist extends Component
{

    public Movie $movie;
    public $watchItem;
    public $movie_db;


    protected $listeners = ['watchItem' => 'store', 'movie_db' => 'destroy'];

    public function mount()
    {
        if(array_key_exists('original_title', $this->watchItem)) {
            if ($this->watchItem['original_title']) {
                $identifiable = $this->watchItem['original_title'];
            } else {
                $identifiable = $this->watchItem['title'];
            }
        }else{
            if ($this->watchItem['original_name']) {
                $identifiable = $this->watchItem['original_name'];
            } else {
                $identifiable = $this->watchItem['name'];
            }
        }


        $the_movie = Movie::where('name', $identifiable)->first();
        $this->watch_item = $the_movie;
//            dd($this->watching);


    }

    public function store()
    {
        // check if user has watch listed this movie / series
        if (array_key_exists('first_air_date', $this->watchItem)) {
            $item = $this->watchItem['original_name'];
        } else {
            $item = $this->watchItem['title'];
        }

        $movie = Movie::where(['name' => $item, 'user_id' => auth()->id()])->first();

        if ($movie) {
            session()->flash('message', 'Already on your watch list');
            return;
        }


        $watchlist = new Movie();
        $watchlist->user_id = auth()->id();
        $watchlist->watch_type = Movie::Watching;
        if (array_key_exists('first_air_date', $this->watchItem)) {
            $watchlist->type = Movie::Series;

            if ($this->watchItem['name']) {
                $watchlist->name = $this->watchItem['name'];
            } else {
                $watchlist->name = $this->watchItem['original_name'];
            }
            $watchlist->release_date = $this->watchItem['first_air_date'];

//            dd($this->watchItem);
            if ($this->watchItem['next_episode_to_air'] == Null) {
                $watchlist->next_air_date = null;
            } else {
                $watchlist->next_air_date = $this->watchItem['next_episode_to_air']['air_date'];
            }

            if ($this->watchItem['last_episode_to_air']['air_date'] == Null) {
                $watchlist->last_air_date = null;
            } else {
                $watchlist->last_air_date = $this->watchItem['last_episode_to_air']['air_date'];
            }


        } else {
            $watchlist->release_date = $this->watchItem['release_date'];
            $watchlist->type = Movie::Movies;
            $watchlist->name = $this->watchItem['title'];
            $watchlist->next_air_date = null;
            $watchlist->last_air_date = null;
        }
//            sleep(2);
        session()->flash('message', 'Added to watch list');

        $watchlist->save();

        return redirect()->back();


    }

    public function destroy()
    {
        $movie = Movie::where(['name' => $this->movie_db->name, 'user_id' => auth()->id()])->delete();
        session()->flash('message', 'removed to watch list');
        return redirect()->back();

    }


    public function render()
    {
        return view('livewire.watchlist');
    }
}
