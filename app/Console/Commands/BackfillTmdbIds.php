<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Link TMDB titles that were saved without their TMDB id (older series were
 * stored with movie_id = null, so the nightly date refresh skipped them).
 *
 * A row is only linked when TMDB returns exactly one confident match on
 * title + release date (or year). Anything ambiguous is reported, not guessed.
 */
class BackfillTmdbIds extends Command
{
    protected $signature = 'movies:backfill-tmdb-ids {--dry-run : Show what would be linked without saving}';

    protected $description = 'Find and save missing TMDB ids for non-custom movies and series';

    public function handle(): int
    {
        $rows = Movie::fromTmdb()
            ->whereNull('movie_id')
            ->whereIn('type', [Movie::Movies, Movie::Series])
            ->orderBy('name')
            ->get();

        if ($rows->isEmpty()) {
            $this->info('Nothing to backfill.');

            return self::SUCCESS;
        }

        $linked = [];
        $skipped = [];

        $this->withProgressBar($rows, function (Movie $movie) use (&$linked, &$skipped) {
            [$tmdbId, $reason] = $this->findMatch($movie);

            if (! $tmdbId) {
                $skipped[] = [$movie->name, $movie->type == Movie::Series ? 'Series' : 'Movie', $movie->release_date ?? '—', $reason];

                return;
            }

            $linked[] = [$movie->name, $movie->type == Movie::Series ? 'Series' : 'Movie', $movie->release_date ?? '—', $tmdbId];

            if (! $this->option('dry-run')) {
                $movie->forceFill(['movie_id' => $tmdbId])->save();
            }
        });

        $this->newLine(2);

        if ($linked) {
            $this->info(($this->option('dry-run') ? 'Would link ' : 'Linked ').count($linked).' titles:');
            $this->table(['Name', 'Type', 'Release date', 'TMDB id'], $linked);
        }

        if ($skipped) {
            $this->warn('Skipped '.count($skipped).' titles (link these by hand with "Sync Data" on the title page):');
            $this->table(['Name', 'Type', 'Release date', 'Reason'], $skipped);
        }

        return self::SUCCESS;
    }

    /** @return array{0: ?int, 1: string} */
    private function findMatch(Movie $movie): array
    {
        $isSeries = (int) $movie->type === Movie::Series;
        $year = $movie->release_date ? substr($movie->release_date, 0, 4) : null;

        $response = Http::withToken(config('services.tmdb.token'))
            ->connectTimeout(5)
            ->timeout(15)
            ->retry(2, 500, throw: false)
            ->get('https://api.themoviedb.org/3/search/'.($isSeries ? 'tv' : 'movie'), array_filter([
                'query' => $movie->name,
                // A year filter is too strict if our stored date is slightly off, so search without it.
            ]));

        if (! $response->successful()) {
            return [null, 'TMDB request failed ('.$response->status().')'];
        }

        $nameKey = $isSeries ? 'name' : 'title';
        $originalKey = $isSeries ? 'original_name' : 'original_title';
        $dateKey = $isSeries ? 'first_air_date' : 'release_date';

        $target = $this->normalize($movie->name);

        $sameName = collect($response->json('results') ?? [])->filter(fn ($r) => $this->normalize($r[$nameKey] ?? '') === $target
            || $this->normalize($r[$originalKey] ?? '') === $target
        );

        if ($sameName->isEmpty()) {
            return [null, 'no exact title match'];
        }

        if ($movie->release_date) {
            $sameDate = $sameName->where($dateKey, $movie->release_date);
            if ($sameDate->count() === 1) {
                return [(int) $sameDate->first()['id'], 'title + date'];
            }

            $sameYear = $sameName->filter(fn ($r) => substr($r[$dateKey] ?? '', 0, 4) === $year);
            if ($sameYear->count() === 1) {
                return [(int) $sameYear->first()['id'], 'title + year'];
            }

            if ($sameDate->count() > 1 || $sameYear->count() > 1) {
                return [null, 'several matches with the same title and year'];
            }

            return [null, 'title matches but release year differs'];
        }

        return $sameName->count() === 1
            ? [(int) $sameName->first()['id'], 'title only']
            : [null, 'several matches and no release date to tell them apart'];
    }

    private function normalize(string $value): string
    {
        return Str::of($value)->lower()->ascii()->replaceMatches('/[^a-z0-9]+/', ' ')->trim()->toString();
    }
}
