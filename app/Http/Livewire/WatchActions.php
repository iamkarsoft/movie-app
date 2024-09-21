<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
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

        $watchStatus = MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->where('movies.name', $identifiable)
            ->where('movie_user.user_id', auth()->user()->id)
            ->first();

        if ($watchStatus) {
            $watchStatus->watch_type = $data;
        }

        toast()
            ->success('Status Updated', 'Notification')
            ->push();

        $watchStatus->save();

        $this->emit('refreshStoreMovie');

    }

    public function render()
    {
        return view('livewire.watch-actions');
    }
}
