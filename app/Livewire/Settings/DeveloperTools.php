<?php

namespace App\Livewire\Settings;

use App\Jobs\RunArtisanCommand;
use Livewire\Component;

class DeveloperTools extends Component
{
    public array $statuses = [];

    public function mount(): void
    {
        $this->statuses = [];
    }

    protected array $commands = [
        [
            'key'         => 'movies:update',
            'name'        => 'Update Movie Data',
            'description' => 'Fetches and updates release dates, last aired dates, and next air dates for all watchlisted movies and series from TMDB.',
        ],
        [
            'key'         => 'movies:notifymember',
            'name'        => 'Notify Members',
            'description' => 'Sends email notifications to users about movies or series releasing or airing today.',
        ],
        [
            'key'         => 'movies:merge-duplicates',
            'name'        => 'Merge Duplicate Titles',
            'description' => 'Links titles missing a TMDB id, then merges rows that are the same movie or series, keeping watch status and season/episode progress. Saves an undo log to storage/app/duplicate-merges.',
        ],
        [
            'key'         => 'movies:backfill-tmdb-ids',
            'name'        => 'Link Missing TMDB IDs',
            'description' => 'Looks up titles saved without a TMDB id and links them when there is exactly one match on title and release date, so they get nightly date updates.',
        ],
    ];

    public function queueCommand(string $key): void
    {
        $allowed = array_column($this->commands, 'key');
        if (! in_array($key, $allowed, true)) {
            return;
        }

        $command = collect($this->commands)->firstWhere('key', $key);
        $label = $command['name'] ?? $key;

        RunArtisanCommand::dispatch($key, $label)->onQueue('default');
        $this->statuses[$key] = 'queued';
    }

    public function render()
    {
        return view('livewire.settings.developer-tools', [
            'commands' => $this->commands,
        ]);
    }
}
