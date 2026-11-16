<?php

namespace Tests\Feature;

use App\Livewire\Watchlist;
use App\Models\Movie;
use App\Models\MovieUser;
use App\Models\User;
use App\Services\TmdbTitleResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class DuplicateTitlesTest extends TestCase
{
    use RefreshDatabase;

    /** TMDB tv payload where original_name differs from the stored name. */
    private array $aoashi = [
        'id' => 126437,
        'name' => 'Aoashi',
        'original_name' => 'アオアシ',
        'first_air_date' => '2022-04-09',
    ];

    private function user(): User
    {
        return User::create(['name' => 'T', 'email' => Str::random(8).'@example.com', 'password' => bcrypt('x')]);
    }

    private function track(User $user, Movie $movie, array $pivot = []): MovieUser
    {
        return MovieUser::create($pivot + ['movie_id' => $movie->id, 'user_id' => $user->id, 'watch_type' => 1]);
    }

    public function test_tracked_title_is_found_by_tmdb_id_even_when_original_name_differs(): void
    {
        $user = $this->user();
        $movie = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series, 'movie_id' => 126437]);
        $this->track($user, $movie, ['season' => 1, 'episode' => 3]);

        $found = app(TmdbTitleResolver::class)->tracked($this->aoashi, $user->id);

        $this->assertNotNull($found);
        $this->assertSame($movie->id, $found->movie_id); // pivot's movie uuid, used by Update Episodes
        $this->assertSame(3, (int) $found->episode);
    }

    public function test_old_row_without_tmdb_id_is_matched_by_name_and_linked(): void
    {
        $movie = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series]);

        $this->assertSame($movie->id, app(TmdbTitleResolver::class)->find($this->aoashi)?->id);
        $this->assertSame(126437, (int) $movie->fresh()->movie_id);
    }

    public function test_adding_a_renamed_title_reuses_the_existing_row(): void
    {
        $user = $this->user();
        Movie::create(['name' => 'Guy Ritchie\'s The Covenant', 'type' => Movie::Movies, 'movie_id' => 882569]);

        Livewire::actingAs($user)
            ->test(Watchlist::class, ['watchItem' => ['id' => 882569, 'title' => 'The Covenant', 'release_date' => '2023-04-19']])
            ->assertSet('isInWatchlist', false)
            ->call('toggleWatchlist')
            ->assertSet('isInWatchlist', true)
            ->call('toggleWatchlist')
            ->assertSet('isInWatchlist', false);

        $this->assertSame(1, Movie::where('movie_id', 882569)->count());
    }

    public function test_tv_page_shows_episode_controls_for_tracked_show(): void
    {
        Http::fake(['*' => Http::response($this->aoashi + [
            'overview' => '', 'genres' => [], 'seasons' => [], 'vote_average' => 7.8,
            'credits' => ['cast' => [], 'crew' => []], 'videos' => ['results' => []], 'images' => ['backdrops' => []],
            'poster_path' => null, 'backdrop_path' => null, 'created_by' => [],
        ])]);

        $user = $this->user();
        $movie = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series, 'movie_id' => 126437]);
        $this->track($user, $movie);

        $this->actingAs($user)->get(route('tv.show', 126437))
            ->assertOk()
            ->assertSee('Update Episodes');
    }

    public function test_merge_keeps_oldest_row_and_users_progress(): void
    {
        $user = $this->user();
        $other = $this->user();

        $old = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series, 'movie_id' => 126437]);
        $old->forceFill(['created_at' => now()->subYear()])->save();
        $dupe = Movie::create(['name' => 'アオアシ', 'type' => Movie::Series, 'movie_id' => 126437]);
        $unlinked = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series]); // saved without id
        $custom = Movie::create(['name' => 'Aoashi', 'type' => Movie::Series, 'is_custom' => true, 'created_by' => $user->id]);

        $this->track($user, $old, ['season' => 1, 'episode' => 2, 'updated_at' => now()->subDay()]);
        $this->track($user, $dupe, ['season' => 2, 'episode' => 5, 'watch_type' => Movie::Watched]);
        $this->track($other, $unlinked);

        Http::fake(['*' => Http::response(['results' => [$this->aoashi]])]);

        $this->artisan('movies:merge-duplicates')->assertSuccessful();

        $this->assertSame(1, Movie::fromTmdb()->where('name', 'like', '%')->where('type', Movie::Series)->count());
        $this->assertNotNull($old->fresh());
        $this->assertNotNull($custom->fresh(), 'custom entries are never merged');

        $mine = MovieUser::where('user_id', $user->id)->where('movie_id', $old->id)->firstOrFail();
        $this->assertSame([2, 5, Movie::Watched], [(int) $mine->season, (int) $mine->episode, (int) $mine->watch_type]);
        $this->assertSame(1, MovieUser::where('user_id', $other->id)->where('movie_id', $old->id)->count());
    }

    public function test_merge_dry_run_changes_nothing(): void
    {
        Movie::create(['name' => 'A', 'type' => Movie::Series, 'movie_id' => 5]);
        Movie::create(['name' => 'A', 'type' => Movie::Series, 'movie_id' => 5]);

        $this->artisan('movies:merge-duplicates --dry-run')->assertSuccessful();

        $this->assertSame(2, Movie::where('movie_id', 5)->count());
    }
}
