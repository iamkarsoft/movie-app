<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Watchlist extends Component
{
    use WireToast;

    public Movie $movie;
    public $watchItem;
    public $movie_db;
    protected $listeners = ['watchItem' => 'store', 'movie_db' => 'destroy', 'refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->updateWatchlistStatus();
    }

    public function updateWatchlistStatus(): void
    {
        $item = array_key_exists('first_air_date', $this->watchItem)
            ? $this->watchItem['original_name']
            : $this->watchItem['title'];

        $movie = Movie::where('name', $item)->first();

        if ($movie && auth()->check()) {
            $this->isInWatchlist = MovieUser::where('movie_id', $movie->id)
                ->where('user_id', auth()->id())
                ->exists();
        }
    }

    public function toggleWatchlist(): void
    {
        if (!auth()->check()) {
            toast()
                ->info('Please login to use the watchlist feature.', 'Notification')
                ->push();
            return;
        }

        $item = array_key_exists('first_air_date', $this->watchItem)
            ? $this->watchItem['original_name']
            : $this->watchItem['title'];

        $movie = Movie::where('name', $item)->first();

        if (!$movie) {
            $movie = $this->createMovie();
        }

        $existingEntry = MovieUser::where('movie_id', $movie->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingEntry) {
            $existingEntry->delete();
            toast()
                ->success('Removed from watch list', 'Notification')
                ->push();
        } else {
            auth()->user()->movies()->attach($movie->id);
            toast()
                ->success('Added to watch list', 'Notification')
                ->push();
        }

        $this->updateWatchlistStatus();
    }

    private function createMovie()
    {
        $watchlist = new Movie();

        if (isset($this->watchItem['first_air_date'])) {
            $watchlist->type = Movie::Series;
            $watchlist->name = $this->watchItem['name'] ?? $this->watchItem['original_name'];
            $watchlist->release_date = $this->watchItem['first_air_date'];
            $watchlist->next_air_date = $this->watchItem['next_episode_to_air']['air_date'] ?? null;
            $watchlist->last_air_date = $this->watchItem['last_episode_to_air']['air_date'] ?? null;
        } else {
            $watchlist->type = Movie::Movies;
            $watchlist->name = $this->watchItem['title'];
            $watchlist->release_date = $this->watchItem['release_date'];
            $watchlist->movie_id = $this->watchItem['id'];
            $watchlist->next_air_date = null;
            $watchlist->last_air_date = null;
        }

        $watchlist->save();
        ray($watchlist);
        return $watchlist;
    }

    public function render()
    {
        return view('livewire.watchlist');
    }
}
