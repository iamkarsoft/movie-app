<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Merge `movies` rows that point at the same TMDB title (same TMDB id + type).
 *
 * The oldest row is kept. Watchlist entries on the duplicates are moved onto it;
 * when a user has an entry on both, the entries are combined so no progress is
 * lost (latest status, furthest season/episode). Everything that is changed or
 * deleted is written to storage/app/duplicate-merges/*.json first so it can be
 * restored by hand if needed. Custom entries are never touched.
 */
class MergeDuplicateMovies extends Command
{
    protected $signature = 'movies:merge-duplicates {--dry-run : Show what would be merged without changing anything}
        {--skip-backfill : Do not look up missing TMDB ids before merging}';

    protected $description = 'Merge duplicate movies/series that share the same TMDB id';

    public function handle(): int
    {
        if (! $this->option('dry-run') && ! $this->option('skip-backfill')) {
            // Link rows saved without a TMDB id first, so their copies are grouped by id too.
            $this->call('movies:backfill-tmdb-ids');
        }

        $groups = $this->duplicateGroups();

        if ($groups->isEmpty()) {
            $this->info('No duplicates found.');

            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');
        $log = [];
        $summary = [];

        foreach ($groups as $rows) {
            $keep = $rows->first();
            $duplicates = $rows->slice(1);

            $summary[] = [
                $keep->name,
                $keep->type == Movie::Series ? 'Series' : 'Movie',
                $keep->movie_id ?? '—',
                $rows->count(),
                $duplicates->pluck('name')->unique()->implode(' | '),
            ];

            if ($dryRun) {
                continue;
            }

            $log[] = DB::transaction(fn () => $this->mergeGroup($keep, $duplicates));
        }

        $this->table(['Kept', 'Type', 'TMDB id', 'Rows', 'Duplicate names'], $summary);

        if ($dryRun) {
            $this->info('Dry run: '.count($summary).' titles have duplicates. Nothing was changed.');

            return self::SUCCESS;
        }

        $path = 'duplicate-merges/'.now()->format('Y-m-d-His').'.json';
        Storage::disk('local')->put($path, json_encode($log, JSON_PRETTY_PRINT));

        $removed = collect($summary)->sum(fn ($row) => $row[3] - 1);
        $this->info("Merged {$removed} duplicate rows across ".count($summary).' titles. Undo log: '.Storage::disk('local')->path($path));

        return self::SUCCESS;
    }

    /**
     * Rows that are the same title: same TMDB id + type, or (for rows still
     * missing a TMDB id) the exact same name + type. Oldest row first.
     *
     * @return Collection<int, Collection<int, Movie>>
     */
    private function duplicateGroups(): Collection
    {
        $byId = Movie::fromTmdb()->whereNotNull('movie_id')
            ->select('movie_id', 'type')->groupBy('movie_id', 'type')->havingRaw('COUNT(*) > 1')->get()
            ->map(fn ($g) => Movie::fromTmdb()->where('movie_id', $g->movie_id)->where('type', $g->type)
                ->orderBy('created_at')->get());

        $byName = Movie::fromTmdb()->whereNull('movie_id')
            ->select('name', 'type')->groupBy('name', 'type')->havingRaw('COUNT(*) > 1')->get()
            ->map(fn ($g) => Movie::fromTmdb()->whereNull('movie_id')->where('name', $g->name)->where('type', $g->type)
                ->orderBy('created_at')->get());

        return $byId->concat($byName)->values();
    }

    private function mergeGroup(Movie $keep, Collection $duplicates): array
    {
        $entry = [
            'kept' => $keep->toArray(),
            'kept_pivots_before' => MovieUser::where('movie_id', $keep->id)->get()->toArray(),
            'deleted_movies' => $duplicates->map->toArray()->values()->all(),
            'duplicate_pivots' => MovieUser::whereIn('movie_id', $duplicates->pluck('id'))->get()->toArray(),
        ];

        foreach ($duplicates as $duplicate) {
            foreach (MovieUser::where('movie_id', $duplicate->id)->get() as $pivot) {
                $existing = MovieUser::where('movie_id', $keep->id)->where('user_id', $pivot->user_id)->first();

                if (! $existing) {
                    $pivot->update(['movie_id' => $keep->id]);

                    continue;
                }

                $existing->update($this->combine($existing, $pivot));
                $pivot->delete();
            }

            // Keep the dates of whichever row was refreshed most recently.
            if ($duplicate->updated_at > $keep->updated_at) {
                $keep->forceFill($duplicate->only(['release_date', 'last_air_date', 'next_air_date']))->save();
            }

            $duplicate->delete();
        }

        return $entry;
    }

    /** Combine two watchlist entries for the same user without losing progress. */
    private function combine(MovieUser $a, MovieUser $b): array
    {
        $latest = $b->updated_at > $a->updated_at ? $b : $a;

        $progress = collect([$a, $b])
            ->filter(fn ($p) => $p->season !== null || $p->episode !== null)
            ->sortByDesc(fn ($p) => [(int) $p->season, (int) $p->episode])
            ->first();

        return [
            'watch_type' => $latest->watch_type,
            'season' => $progress?->season,
            'episode' => $progress?->episode,
        ];
    }
}
