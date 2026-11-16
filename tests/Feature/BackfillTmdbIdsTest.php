<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BackfillTmdbIdsTest extends TestCase
{
    use RefreshDatabase;

    public function test_links_only_confident_matches_and_skips_custom_entries(): void
    {
        Http::fake([
            'api.themoviedb.org/3/search/tv*' => Http::response(['results' => [
                ['id' => 60574, 'name' => 'Peaky Blinders', 'original_name' => 'Peaky Blinders', 'first_air_date' => '2013-09-12'],
                ['id' => 1, 'name' => 'Peaky Blinders: Extras', 'original_name' => 'x', 'first_air_date' => '2013-09-12'],
            ]]),
            'api.themoviedb.org/3/search/movie*' => Http::response(['results' => [
                ['id' => 10, 'title' => 'Prey', 'original_title' => 'Prey', 'release_date' => '2022-08-02'],
                ['id' => 11, 'title' => 'Prey', 'original_title' => 'Prey', 'release_date' => '2022-03-01'],
            ]]),
        ]);

        $series = Movie::create(['name' => 'Peaky Blinders', 'type' => Movie::Series, 'release_date' => '2013-09-12']);
        $ambiguous = Movie::create(['name' => 'Prey', 'type' => Movie::Movies, 'release_date' => '2022-06-01']);
        $custom = Movie::create(['name' => 'Peaky Blinders', 'type' => Movie::Series, 'is_custom' => true]);

        $this->artisan('movies:backfill-tmdb-ids')->assertSuccessful();

        $this->assertSame(60574, (int) $series->fresh()->movie_id);
        $this->assertNull($ambiguous->fresh()->movie_id);
        $this->assertNull($custom->fresh()->movie_id);
    }

    public function test_dry_run_saves_nothing(): void
    {
        Http::fake(['*' => Http::response(['results' => [
            ['id' => 60574, 'name' => 'Peaky Blinders', 'original_name' => 'Peaky Blinders', 'first_air_date' => '2013-09-12'],
        ]])]);

        $series = Movie::create(['name' => 'Peaky Blinders', 'type' => Movie::Series, 'release_date' => '2013-09-12']);

        $this->artisan('movies:backfill-tmdb-ids --dry-run')->assertSuccessful();

        $this->assertNull($series->fresh()->movie_id);
    }
}
