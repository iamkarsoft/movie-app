<?php

namespace App\Livewire;

use App\Models\Movie;
use App\Models\MovieUser;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Create / edit / delete movies and series that are not on TMDB.
 *
 * Custom entries are owned by the user who created them and are never
 * touched by the nightly TMDB refresh, so their dates are managed here.
 */
class CustomEntryForm extends Component
{
    public const MODAL = 'custom-entry';

    public ?string $movieId = null;

    public string $name = '';

    public int $type = Movie::Movies;

    public ?string $release_date = null;

    public ?string $last_air_date = null;

    public ?string $next_air_date = null;

    public int $watch_type = Movie::onWatchlist;

    /** Open the form on page load (e.g. coming from a search with no TMDB match). */
    public bool $openOnLoad = false;

    public function mount(?string $prefill = null): void
    {
        $prefill = trim((string) $prefill);

        if ($prefill !== '') {
            $this->name = mb_substr($prefill, 0, 255);
            $this->openOnLoad = true;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'integer', Rule::in(array_keys(Movie::TYPE_LABELS))],
            'release_date' => ['nullable', 'date'],
            'last_air_date' => ['nullable', 'date'],
            'next_air_date' => ['nullable', 'date'],
            'watch_type' => ['required', 'integer', Rule::in([
                Movie::onWatchlist, Movie::Watching, Movie::Watched, Movie::Abandoned,
            ])],
        ];
    }

    #[On('create-custom-entry')]
    public function create(): void
    {
        $this->resetForm();
        Flux::modal(self::MODAL)->show();
    }

    #[On('edit-custom-entry')]
    public function edit(string $id): void
    {
        $this->resetForm();

        $movie = $this->ownedEntry($id);
        $pivot = MovieUser::where('movie_id', $movie->id)->where('user_id', auth()->id())->first();

        $this->movieId = $movie->id;
        $this->name = $movie->name;
        $this->type = (int) $movie->type;
        $this->release_date = $movie->release_date;
        $this->last_air_date = $movie->last_air_date;
        $this->next_air_date = $movie->next_air_date;
        $this->watch_type = (int) ($pivot->watch_type ?? Movie::onWatchlist);

        Flux::modal(self::MODAL)->show();
    }

    public function save(): void
    {
        $data = $this->validate();

        $attributes = [
            'name' => trim($data['name']),
            'type' => $data['type'],
            'release_date' => $data['release_date'] ?: null,
            // Only series-like entries have episode air dates.
            'last_air_date' => $this->isEpisodic() ? ($data['last_air_date'] ?: null) : null,
            'next_air_date' => $this->isEpisodic() ? ($data['next_air_date'] ?: null) : null,
        ];

        DB::transaction(function () use ($attributes, $data) {
            if ($this->movieId) {
                $movie = $this->ownedEntry($this->movieId);
                $movie->update($attributes);
            } else {
                $movie = Movie::create($attributes + [
                    'is_custom' => true,
                    'created_by' => auth()->id(),
                    'movie_id' => null,
                ]);
            }

            MovieUser::updateOrCreate(
                ['movie_id' => $movie->id, 'user_id' => auth()->id()],
                ['watch_type' => $data['watch_type']],
            );
        });

        $message = $this->movieId ? 'Custom entry updated' : 'Custom entry added';

        Flux::modal(self::MODAL)->close();
        $this->resetForm();
        $this->dispatch('custom-entry-saved', message: $message);
    }

    public function delete(): void
    {
        if (! $this->movieId) {
            return;
        }

        $movie = $this->ownedEntry($this->movieId);

        DB::transaction(function () use ($movie) {
            MovieUser::where('movie_id', $movie->id)->delete();
            $movie->delete();
        });

        Flux::modal(self::MODAL)->close();
        $this->resetForm();
        $this->dispatch('custom-entry-saved', message: 'Custom entry deleted');
    }

    public function isEpisodic(): bool
    {
        return $this->type !== Movie::Movies;
    }

    private function ownedEntry(string $id): Movie
    {
        return Movie::where('id', $id)
            ->where('is_custom', true)
            ->where('created_by', auth()->id())
            ->firstOrFail();
    }

    private function resetForm(): void
    {
        $this->reset(['movieId', 'name', 'type', 'release_date', 'last_air_date', 'next_air_date', 'watch_type']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.custom-entry-form', [
            'typeLabels' => Movie::TYPE_LABELS,
        ]);
    }
}
