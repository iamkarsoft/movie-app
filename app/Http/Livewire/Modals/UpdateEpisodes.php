<?php

namespace App\Http\Livewire\Modals;

use App\Models\Movie;
use Livewire\Component;

class UpdateEpisodes extends Component
{
    public Movie $movie;

    public $isOpen = false;

    public $movie_db;

    public $season;

    public $episode;

    public function mount($movie_db)
    {
        $this->movie_db = $movie_db;
        $this->episode = $this->movie_db['episode'];
        $this->season = $this->movie_db['season'];
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function saveData()
    {
        $this->movie_db['episode'] = $this->episode;
        $this->movie_db['season'] = $this->season;
        $this->movie_db->save();
        $this->closeModal();

        ray($this->movie_db);
    }

    public function render()
    {
        return view('livewire.modals.update-episodes');
    }
}
