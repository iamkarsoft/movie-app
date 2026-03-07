<?php

namespace App\Livewire\Modals;

use Livewire\Component;

class Trailer extends Component
{
    public $movie;

    public $visible = false;

    public function show()
    {
        $this->visible = true;
    }

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
