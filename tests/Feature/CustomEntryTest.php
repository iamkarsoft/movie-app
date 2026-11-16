<?php

namespace Tests\Feature;

use App\Livewire\CustomEntryForm;
use App\Livewire\MovieDataTable;
use App\Models\Movie;
use App\Models\MovieUser;
use App\Models\User;
use App\Services\MovieDateUpdater;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class CustomEntryTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::create([
            'name' => 'Tester',
            'email' => Str::random(8).'@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_user_can_add_a_custom_series_to_their_watchlist(): void
    {
        $user = $this->user();

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class)
            ->call('create')
            ->set('name', 'Local Drama')
            ->set('type', Movie::Series)
            ->set('release_date', '2026-01-10')
            ->set('next_air_date', '2026-11-20')
            ->set('watch_type', Movie::Watching)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('custom-entry-saved');

        $movie = Movie::where('name', 'Local Drama')->firstOrFail();
        $this->assertTrue($movie->is_custom);
        $this->assertSame($user->id, $movie->created_by);
        $this->assertNull($movie->movie_id);
        $this->assertSame('2026-11-20', $movie->next_air_date);

        $this->assertDatabaseHas('movie_user', [
            'movie_id' => $movie->id,
            'user_id' => $user->id,
            'watch_type' => Movie::Watching,
        ]);
    }

    public function test_movie_type_drops_episode_dates_and_name_is_required(): void
    {
        $user = $this->user();

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class)
            ->set('name', 'Indie Film')
            ->set('type', Movie::Movies)
            ->set('next_air_date', '2026-12-01')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNull(Movie::where('name', 'Indie Film')->value('next_air_date'));
    }

    public function test_owner_can_edit_and_delete_their_custom_entry(): void
    {
        $user = $this->user();
        $movie = $this->customEntryFor($user);

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class)
            ->call('edit', $movie->id)
            ->assertSet('name', 'Original')
            ->set('name', 'Renamed')
            ->set('watch_type', Movie::Watched)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Renamed', $movie->fresh()->name);
        $this->assertSame(Movie::Watched, (int) MovieUser::where('movie_id', $movie->id)->value('watch_type'));

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class)
            ->call('edit', $movie->id)
            ->call('delete');

        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
        $this->assertDatabaseMissing('movie_user', ['movie_id' => $movie->id]);
    }

    public function test_other_users_cannot_edit_someone_elses_custom_entry(): void
    {
        $movie = $this->customEntryFor($this->user());

        $this->expectException(ModelNotFoundException::class);

        Livewire::actingAs($this->user())
            ->test(CustomEntryForm::class)
            ->call('edit', $movie->id);
    }

    public function test_tmdb_refresh_never_touches_custom_entries(): void
    {
        Http::fake();

        $movie = $this->customEntryFor($this->user());

        (new MovieDateUpdater())->run();

        Http::assertNothingSent();
        $this->assertSame('2026-05-01', $movie->fresh()->release_date);
    }

    public function test_custom_entry_shows_in_watchlist_table(): void
    {
        $user = $this->user();
        $this->customEntryFor($user);

        Livewire::actingAs($user)
            ->test(MovieDataTable::class, ['model' => MovieUser::class])
            ->assertSee('Original')
            ->assertSee('Custom')
            ->assertSee('Add custom entry');
    }

    public function test_watchlist_page_renders_with_the_form(): void
    {
        $user = $this->user();
        $this->customEntryFor($user);

        $this->actingAs($user)
            ->get(route('watchlist'))
            ->assertOk()
            ->assertSeeLivewire(CustomEntryForm::class)
            ->assertSee('Add custom entry');
    }

    public function test_search_link_opens_form_prefilled(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->get(route('watchlist', ['add' => 'Obscure Show']))
            ->assertOk()
            ->assertSee('Obscure Show');

        Livewire::actingAs($user)
            ->test(CustomEntryForm::class, ['prefill' => '  Obscure Show '])
            ->assertSet('name', 'Obscure Show')
            ->assertSet('openOnLoad', true);
    }

    private function customEntryFor(User $user): Movie
    {
        $movie = Movie::create([
            'name' => 'Original',
            'type' => Movie::Series,
            'release_date' => '2026-05-01',
            'is_custom' => true,
            'created_by' => $user->id,
        ]);

        MovieUser::create(['movie_id' => $movie->id, 'user_id' => $user->id, 'watch_type' => Movie::onWatchlist]);

        return $movie;
    }
}
