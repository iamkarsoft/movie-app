<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Models\User;
use App\Notifications\ReleaseDayNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;

class NotifyMember extends Command
{
    use Notifiable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:notifymember';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send notifications about releases and episodes';

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
     */
    public function handle()
    {
        $users = User::all();
        foreach($users as $user){
            $upcomings = Movie::where('release_date', '=', Carbon::today())
                ->where('user_id',$user->id)
                ->get();


        $episodes = Movie::where('next_air_date', '=', Carbon::today())
            ->where('user_id',$user->id)
            ->get();
              $user->notify(new ReleaseDayNotification($upcomings,$episodes));
        }
    }
}
