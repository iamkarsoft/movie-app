<?php

namespace App\Http\Livewire;

use App\Models\Movie;
use Livewire\Component;

class WatchActions extends Component
{
    public Movie $movie;
    public $status;
    public $watching;

     protected $listeners = ['status' => 'watched'];





    public function watched(){


         if($this->status['original_title']){
            $identifiable = $this->status['original_title'];
        }else{
            $identifiable = $this->status['title'];
        }

        $watchStatus = Movie::where('name',$identifiable)->first();

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

         session()->flash('message', 'Updated');

            $watchStatus->save();

            return redirect()->back();
    }


    public function render()
    {
        return view('livewire.watch-actions');
    }
}
