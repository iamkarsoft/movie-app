<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;

class UpdateMovieData extends Component
{
    public Movie $movie;

    public $updatemovie;

    // public function mount()
    // {
    //     if (array_key_exists('original_title', $this->updatemovie)) {
    //         if ($this->updatemovie['original_title']) {
    //             $identifiable = $this->updatemovie['original_title'];
    //         } else {
    //             $identifiable = $this->updatemovie['title'];
    //         }
    //     } else {
    //         if ($this->updatemovie['original_name']) {
    //             $identifiable = $this->updatemovie['original_name'];
    //         } else {
    //             $identifiable = $this->updatemovie['name'];
    //         }
    //     }

    //     $movie_to_update = Movie::where('name', $identifiable)->first();
    //     if ($movie_to_update) {
    //         $movie_to_update->movie_id = $this->updatemovie['id'];
    //         $movie_to_update->save();
    //     }

    //     if ($movie_to_update && $movie_to_update->type == 1) {
    //         if ($this->updatemovie['next_episode_to_air'] !== null) {
    //             $movie_to_update->last_air_date = $this->updatemovie['last_episode_to_air']['air_date'];
    //         }
    //         if ($this->updatemovie['next_episode_to_air'] !== null) {
    //             $movie_to_update->next_air_date = $this->updatemovie['next_episode_to_air']['air_date'];
    //         }
    //         $movie_to_update->save();
    //     }
    // }

    public function render()
    {
        return view('livewire.update-movie-data');
    }
}
