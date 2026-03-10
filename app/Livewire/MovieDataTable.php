<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MovieUser;
use Livewire\WithPagination;

class MovieDataTable extends Component
{
    use WithPagination;
    public $model;
    public $paginated;
    public $query;

    public function mount($model, $paginate=10)
    {
        $this->model = $model;
        $this->paginated = $paginate;

        // ray(MovieUser::query()->join('movies', 'movies.id', '=', 'movie_user.movie_id')
        //     ->join('users', 'users.id', '=', 'movie_user.user_id')
        //     ->select('users.*', 'movies.*', 'movie_user.*', 'movie_user.watch_type as watch_type', 'movies.movie_id as movie_id')
        //     ->where('movie_user.user_id', auth()->user()->id)
        //     ->search($this->query));
    }

    public function builder()
    {
        return MovieUser::query()->join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*', 'movie_user.watch_type as watch_type', 'movies.movie_id as movie_id')
            ->where('movie_user.user_id', auth()->user()->id)
            ->latest('movie_user.updated_at');
    }

    public function records()
    {
        $builder = $this->builder();

        if ($this->query) {
            $builder = $builder->where('movies.name', 'like', '%' . $this->query . '%');
        }
        return $builder->paginate($this->paginated);
    }



    public function render()
    {
        return view('livewire.movie-data-table');
    }
}
