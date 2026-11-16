<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use App\Services\TmdbTitleResolver;
use Livewire\Component;

class Watchlist extends Component
{
    public $watchItem;

    public $movie_db;

    public $isInWatchlist = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->updateWatchlistStatus();
    }

    public function updateWatchlistStatus()
    {
        $this->isInWatchlist = $this->watchItem && auth()->check()
            && app(TmdbTitleResolver::class)->tracked($this->watchItem, auth()->id()) !== null;
    }

    public function toggleWatchlist()
    {
        if (! auth()->check()) {
            session()->flash('message', 'Please login to use the watchlist feature.');

            return;
        }

        if (! $this->watchItem) {
            session()->flash('message', 'Unable to add to watchlist. Please try again.');

            return;
        }

        $resolver = app(TmdbTitleResolver::class);

        $existingEntry = $resolver->tracked($this->watchItem, auth()->id());

        if ($existingEntry) {
            // tracked() selects movie_user.* last, so id / movie_id are the pivot's.
            MovieUser::whereKey($existingEntry->id)->delete();
            session()->flash('message', 'Removed from watch list');
        } else {
            $movie = $resolver->find($this->watchItem) ?? $this->createMovie();
            auth()->user()->movies()->attach($movie->id);
            session()->flash('message', 'Added to watch list');
        }

        $this->updateWatchlistStatus();

        // Emit Alpine.js event to update UI
        $this->js("
            window.dispatchEvent(new CustomEvent('watchlist-updated', {
                detail: ".($this->isInWatchlist ? 'true' : 'false').'
            }));
        ');
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
            $watchlist->movie_id = $this->watchItem['id'] ?? null;
        } else {
            $watchlist->type = Movie::Movies;
            $watchlist->name = $this->watchItem['title'];
            $watchlist->release_date = $this->watchItem['release_date'];
            $watchlist->movie_id = $this->watchItem['id'];
            $watchlist->next_air_date = null;
            $watchlist->last_air_date = null;
        }

        $watchlist->save();

        return $watchlist;
    }

    public function render()
    {
        return view('livewire.watchlist');
    }
}
