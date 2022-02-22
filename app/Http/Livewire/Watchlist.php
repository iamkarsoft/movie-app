<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;

class Watchlist extends Component
{

    public Movie $movie;
    public $watchItem;


    protected $listeners = ['watchItem' => 'store'];

    public function store()
    {

//        dd($this->watchItem);

        $watchlist = new Movie();
        $watchlist->user_id = auth()->id();
        if(array_key_exists('first_air_date',$this->watchItem)){
            $watchlist->type = Movie::Series;
            $watchlist->name = $this->watchItem['original_name'];
            $watchlist->release_date = $this->watchItem['first_air_date'];
        }else{
            $watchlist->release_date = $this->watchItem['release_date'];
            $watchlist->type = Movie::Movies;
            $watchlist->name = $this->watchItem['title'];
        }

        if($watchlist->save()){
            session()->flash('watchlist-message', 'Added to watch list');
        }




    }


    public function render()
    {
        return view('livewire.watchlist');
    }
}
