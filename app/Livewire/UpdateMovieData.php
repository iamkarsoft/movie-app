<?php

namespace App\Livewire;

use App\Livewire\Traits\IsModal;
use App\Models\Movie;
use Livewire\Component;

class UpdateMovieData extends Component
{
    use IsModal;

    public $updatemovie;

    public bool $inWatchlist = false;

    public function mount($updatemovie, $movie_db = null)
    {
        $this->updatemovie = $updatemovie;
        $this->inWatchlist = ! is_null($movie_db);
    }

    public function syncData()
    {
        $name = $this->updatemovie['original_title']
            ?? $this->updatemovie['title']
            ?? $this->updatemovie['original_name']
            ?? $this->updatemovie['name']
            ?? '';

        $movie = Movie::where('name', $name)->first();

        if (! $movie) {
            $this->dispatch('hide')->self();

            return;
        }

        $movie->movie_id = $this->updatemovie['id'];

        if (isset($this->updatemovie['first_air_date'])) {
            if (isset($this->updatemovie['last_episode_to_air']['air_date'])) {
                $movie->last_air_date = $this->updatemovie['last_episode_to_air']['air_date'];
            }
            if (isset($this->updatemovie['next_episode_to_air']['air_date'])) {
                $movie->next_air_date = $this->updatemovie['next_episode_to_air']['air_date'];
            }
        } elseif (isset($this->updatemovie['release_date'])) {
            $movie->release_date = $this->updatemovie['release_date'];
        }

        $movie->save();

        $this->dispatch('hide')->self();
    }

    public function render()
    {
        return view('livewire.update-movie-data');
    }
}
