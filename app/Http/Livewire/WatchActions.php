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
        if (array_key_exists('original_title', $this->status)) {
            if ($this->status['original_title']) {
                $identifiable = $this->status['original_title'];
            } else {
                $identifiable = $this->status['title'];
            }
        } elseif (array_key_exists('original_name', $this->status)) {
            if ($this->status['original_name']) {
                $identifiable = $this->status['original_name'];
            } else {
                $identifiable = $this->status['name'];
            }
        }

        // $watchStatus = Movie::where('name', $identifiable)->first();

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

        ray($watchStatus->watch_type, $watchStatus);

        $watchStatus->save();

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.watch-actions');
    }
}
