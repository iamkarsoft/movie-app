<?php

namespace App\Livewire\Modals;

use App\Livewire\Traits\IsModal;
use Livewire\Component;

class Trailer extends Component
{
    use IsModal;

    public $movie;

    public function mount($movie)
    {
        $this->movie = $movie;
    }

    public function render()
    {
        return view('livewire.modals.trailer');
    }
}
