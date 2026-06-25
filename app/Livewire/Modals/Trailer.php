<?php

namespace App\Livewire\Modals;

use App\Livewire\Traits\IsModal;
use Livewire\Component;

class Trailer extends Component
{
    use IsModal;

    public string $videoKey = '';

    public function mount($movie)
    {
        $videos = $movie['videos']['results'] ?? [];
        $this->videoKey = $videos[0]['key'] ?? '';
    }

    public function render()
    {
        return view('livewire.modals.trailer');
    }
}
