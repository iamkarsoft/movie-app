<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class WatchActions extends Component
{
    use WireToast;

    public Movie $movie;

    public $status;

    public $watching;

    public $movie_db;

    public $watch_status;

    protected $listeners = ['status' => 'watched', 'refreshStoreMovie' => '$refresh'];

    //

    public function watched($data)
    {
        $identifiable = $this->status['original_title'] ?? $this->status['title'] ??
            $this->status['original_name'] ?? $this->status['name'] ?? null;

        // Normalize dispatched payload to scalar
        if (is_array($data)) {
            $data = $data[0] ?? null;
        }

        $watchStatus = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->where('movies.name', $identifiable)
            ->where('movie_user.user_id', Auth::id())
            ->first();

        if ($watchStatus && $data !== null) {
            $watchStatus->watch_type = $data;
        } else if (!$watchStatus) {
            return;
        }

        toast()
            ->success('Status Updated', 'Notification')
            ->push();

        $watchStatus->save();

        $this->dispatch('refreshStoreMovie');
    }

    public function render()
    {
        return view('livewire.watch-actions');
    }
}
