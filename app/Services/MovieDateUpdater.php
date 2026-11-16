<?php

namespace App\Services;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieDateUpdater
{
    /** How many TMDB requests to run in parallel. */
    private const CONCURRENCY = 20;

    /**
     * Refresh release / air dates for every tracked movie and series.
     *
     * Each TMDB title is fetched once (even if several rows share it), requests
     * run in parallel batches, and a failed request is logged and skipped
     * instead of aborting the whole run.
     *
     * @return array{updated: int, failed: int}
     */
    public function run(): array
    {
        $titles = Movie::query()
            ->whereIn('type', [0, 1])
            ->whereNotNull('movie_id')
            ->select('movie_id', 'type')
            ->distinct()
            ->get();

        $updated = 0;
        $failed = 0;

        foreach ($titles->chunk(self::CONCURRENCY) as $chunk) {
            $responses = Http::pool(fn (Pool $pool) => $chunk->map(
                fn ($title) => $pool->as("{$title->type}:{$title->movie_id}")
                    ->withToken(config('services.tmdb.token'))
                    ->connectTimeout(5)
                    ->timeout(15)
                    ->get($this->endpoint($title->type, $title->movie_id))
            )->all());

            foreach ($chunk as $title) {
                $response = $responses["{$title->type}:{$title->movie_id}"] ?? null;

                if (! $response instanceof Response || ! $response->successful()) {
                    $failed++;
                    Log::warning('movies:update failed to fetch TMDB title', [
                        'movie_id' => $title->movie_id,
                        'type' => $title->type,
                        'error' => $response instanceof \Throwable ? $response->getMessage() : $response?->status(),
                    ]);

                    continue;
                }

                $attributes = $this->attributesFrom((int) $title->type, $response->json() ?? []);
                $attributes['updated_at'] = Carbon::now();

                $updated += Movie::where('movie_id', $title->movie_id)
                    ->where('type', $title->type)
                    ->update($attributes);
            }
        }

        return ['updated' => $updated, 'failed' => $failed];
    }

    private function endpoint(int $type, $movieId): string
    {
        $kind = $type === 0 ? 'movie' : 'tv';

        return "https://api.themoviedb.org/3/{$kind}/{$movieId}";
    }

    private function attributesFrom(int $type, array $data): array
    {
        if ($type === 0) {
            return array_key_exists('release_date', $data)
                ? ['release_date' => $data['release_date'] ?: null]
                : [];
        }

        if (! isset($data['last_episode_to_air'])) {
            return [];
        }

        return [
            'last_air_date' => $data['last_episode_to_air']['air_date'] ?? null,
            'next_air_date' => $data['next_episode_to_air']['air_date'] ?? null,
        ];
    }
}
