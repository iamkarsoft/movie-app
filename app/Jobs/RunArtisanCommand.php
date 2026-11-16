<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunArtisanCommand implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600;

    public int $tries = 1;

    private static array $allowed = [
        'movies:update',
        'movies:notifymember',
        'movies:merge-duplicates',
        'movies:backfill-tmdb-ids',
    ];

    public function __construct(
        public readonly string $command,
        public readonly string $label = '',
    ) {
    }

    public function displayName(): string
    {
        return $this->label ?: $this->command;
    }

    public function handle(): void
    {
        if (! in_array($this->command, self::$allowed, true)) {
            throw new \RuntimeException("Command [{$this->command}] is not allowed.");
        }

        Artisan::call($this->command);

        Log::info("[dev-tools] {$this->displayName()} finished", ['output' => trim(Artisan::output())]);
    }
}
