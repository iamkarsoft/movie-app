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
    ];

    public function queueCommand(string $key): void
    {
        $allowed = array_column($this->commands, 'key');
        if (!in_array($key, $allowed, true)) return;

        $command = collect($this->commands)->firstWhere('key', $key);
        $label   = $command['name'] ?? $key;

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
