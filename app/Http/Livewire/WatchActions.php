<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class WatchActions extends Component
{
    use WireToast;
    public Movie $movie;
    public $status;
    public $watching;
    public $movie_db;

    protected $listeners = ['status' => 'watched', 'refreshStoreMovie' => '$refresh'];


    //

    public function watched()
    {


        dd($this->watch_action);
        if (array_key_exists('original_title', $this->status)) {
            if ($this->status['original_title']) {
                $identifiable = $this->status['original_title'];
            } else {
                $identifiable = $this->status['title'];
            }
        } else {
            if ($this->status['original_name']) {
                $identifiable = $this->status['original_name'];
            } else {
                $identifiable = $this->status['name'];
            }
        }

        $watchStatus = Movie::where('name', $identifiable)->orWhere('name', $this->status['title'])->first();

        if ($watchStatus->type == Movie::Movies || $watchStatus->type == Movie::Award_show || $watchStatus->type == Movie::Documentary) {

            // $watchStatus->watch_type ;

        }

        toast()
            ->success('Status Updated', 'Notification')
            ->push();


        $watchStatus->save();

        return redirect(request()->header('Referer'));
    }


    public function render()
    {
        return view('livewire.watch-actions');
    }
}
