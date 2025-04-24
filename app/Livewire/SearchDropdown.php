<?php

namespace App\Livewire;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];
        $searchTvResults = [];

        if (strlen($this->search) > 2):
            $searchResults = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/search/movie?query='.$this->search)
                ->json()['results'];

            $searchTvResults = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/search/tv?query='.$this->search)
                    ->json()['results'];

        endif;

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(5),
            'searchTvResults' => collect($searchTvResults)->take(5),
        ]);
    }
}
