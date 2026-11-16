<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use App\Services\TmdbTitleResolver;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WatchActions extends Component
{
    public Movie $movie;

    public $status;

    public $watching;

    public $movie_db;

    public $watch_status;

    protected $listeners = ['status' => 'watched', 'refreshStoreMovie' => '$refresh'];

    //

    public function watched($data)
    {
        // Normalize dispatched payload to scalar
        if (is_array($data)) {
            $data = $data[0] ?? null;
        }

        $watchStatus = $this->status
            ? app(TmdbTitleResolver::class)->tracked($this->status, Auth::id())
            : null;

        if ($watchStatus && $data !== null) {
            $watchStatus->watch_type = $data;
        } elseif (! $watchStatus) {
            return;
        }

        // tracked() returns a joined row; update the pivot directly by its id.
        MovieUser::whereKey($watchStatus->id)->update(['watch_type' => $watchStatus->watch_type]);

        session()->flash('message', 'Status Updated');

        $this->dispatch('refreshStoreMovie');
    }

    public function render()
    {
        return view('livewire.watch-actions');
    }
}
