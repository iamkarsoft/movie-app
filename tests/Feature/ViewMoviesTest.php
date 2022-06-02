<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewMoviesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function home_page_shows_info()
    {
        $response = $this->get(route('movies'));

        $response->assertSuccessful();
    }
}
