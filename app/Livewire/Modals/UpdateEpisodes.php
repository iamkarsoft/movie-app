<?php

namespace App\Livewire\Modals;

use App\Models\MovieUser;
use Livewire\Component;
use App\Livewire\Traits\IsModal;

class UpdateEpisodes extends Component
{
    use IsModal;

    public string $movieId;

    public $season;

    public $episode;

    public function mount($movie_db)
    {
        // movie_user.movie_id (UUID) is the last 'movie_id' in SELECT users.*, movies.*, movie_user.*
        $this->movieId = $movie_db->movie_id;
        $this->season = $movie_db->season;
        $this->episode = $movie_db->episode;
    }

    public function saveData()
    {
        MovieUser::where('movie_id', $this->movieId)
            ->where('user_id', auth()->id())
            ->update([
                'season' => $this->season,
                'episode' => $this->episode,
            ]);

        $this->hide();
    }

    public function render()
    {
        return view('livewire.modals.update-episodes');
    }
}
