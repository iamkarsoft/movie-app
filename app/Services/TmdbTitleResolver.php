<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\MovieUser;
use Illuminate\Support\Collection;

/**
 * Finds the local `movies` row for a TMDB payload (movie or tv details).
 *
 * Matching is done on the TMDB id + type, which never changes. Titles are
 * only used as a fallback for old rows that were saved without a TMDB id,
 * and such a row gets its id filled in the first time it is matched.
 * (Matching on title used to create duplicates whenever TMDB's title differed
 * from the stored one, e.g. original_name "アオアシ" vs name "Aoashi".)
 */
class TmdbTitleResolver
{
    public function typeOf(array $item): int
    {
        // TMDB movie payloads have "title"; tv payloads have "name".
        return array_key_exists('title', $item) ? Movie::Movies : Movie::Series;
    }

    /** All local rows that represent this TMDB title (oldest first). */
    public function candidates(array $item): Collection
    {
        $type = $this->typeOf($item);
        $tmdbId = $item['id'] ?? null;

        $byId = $tmdbId
            ? Movie::fromTmdb()->where('movie_id', $tmdbId)->where('type', $type)->oldest()->get()
            : collect();

        $names = array_values(array_unique(array_filter([
            $item['title'] ?? null,
            $item['name'] ?? null,
            $item['original_title'] ?? null,
            $item['original_name'] ?? null,
        ])));

        $unlinked = $names
            ? Movie::fromTmdb()->whereNull('movie_id')->where('type', $type)->whereIn('name', $names)->oldest()->get()
            : collect();

        if ($tmdbId) {
            // Self-heal: link old rows to their TMDB id now that we know it.
            $unlinked->each(fn (Movie $m) => $m->forceFill(['movie_id' => $tmdbId])->save());
        }

        return $byId->concat($unlinked)->sortBy('created_at')->values();
    }

    /** The canonical local row for this title, if it exists. */
    public function find(array $item): ?Movie
    {
        return $this->candidates($item)->first();
    }

    /**
     * The user's watchlist row for this title, shaped the way the title pages
     * expect it (users.*, movies.*, movie_user.* — so `movie_id` is the movie UUID).
     */
    public function tracked(array $item, ?string $userId): ?MovieUser
    {
        if (! $userId) {
            return null;
        }

        $ids = $this->candidates($item)->pluck('id');

        if ($ids->isEmpty()) {
            return null;
        }

        return MovieUser::join('movies', 'movies.id', '=', 'movie_user.movie_id')
            ->join('users', 'users.id', '=', 'movie_user.user_id')
            ->select('users.*', 'movies.*', 'movie_user.*')
            ->whereIn('movie_user.movie_id', $ids)
            ->where('movie_user.user_id', $userId)
            ->orderByDesc('movie_user.updated_at')
            ->first();
    }
}
