<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class Trailer extends Component
{
    public $movie;

    public $visible = false;

    #[On('show')]
    public function show(){
        $this->visible = true;
    }

    #[On('hide')]
    public function hide()
    {
        $this->visible = false;
    }

    public function mount($movie)
    {
        $this->movie = $movie;
    }

    public function render()
    {
        return view('livewire.modals.trailer');
    }
}
