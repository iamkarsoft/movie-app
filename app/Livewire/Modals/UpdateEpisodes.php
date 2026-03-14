<?php

namespace App\Livewire\Modals;

use App\Models\Movie;
use Livewire\Component;
use App\Livewire\Traits\IsModal;

class UpdateEpisodes extends Component
{
    use IsModal;

    public Movie $movie;


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
        $this->visible = false;
    }

    public function saveData()
    {
        $this->movie_db['episode'] = $this->episode;
        $this->movie_db['season'] = $this->season;
        $this->movie_db->save();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modals.update-episodes');
    }
}
