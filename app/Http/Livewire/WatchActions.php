<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;

class WatchActions extends Component
{
    public Movie $movie;
    public $status;
    public $watching;
     public $movie_db;

     protected $listeners = ['status' => 'watched'];


//

    public function watched(){

        if(array_key_exists('original_title', $this->status)) {
            if ($this->status['original_title']) {
                $identifiable = $this->status['original_title'];
            } else {
                $identifiable = $this->status['title'];
            }
        }else{
            if ($this->status['original_name']) {
                $identifiable = $this->status['original_name'];
            } else {
                $identifiable = $this->status['name'];
            }
        }

        $watchStatus = Movie::where('name',$identifiable)->orWhere('name',$this->status['title'])->first();
        if($watchStatus->type==Movie::Movies || $watchStatus->type==Movie::Award_show || $watchStatus->type==Movie::Documentary){
            if($watchStatus->watch_type!=Movie::Watched){
                $watchStatus->watch_type= Movie::Watched;
            }else{
                $watchStatus->watch_type = Movie::Watching;
            }
        }else{
              if($watchStatus->watch_type!=Movie::Watching){
                $watchStatus->watch_type= Movie::Watching;
            }else{
                $watchStatus->watch_type = Movie::Abandoned;
            }
        }

//         session()->flash('message', 'Updated');
          toast()
            ->success('Status Updated', 'Notification')
            ->push();

            $watchStatus->save();

            return redirect()->back();
    }


    public function render()
    {
        return view('livewire.watch-actions');
    }
}
