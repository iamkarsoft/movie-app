<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update the release, last aired and next air dates for all movies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = app()->make('App\Http\Controllers\UpdateMoviesController');
        app()->call($controller);

        return 0;
    }
}
