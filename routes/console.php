<?php

use App\Console\Commands\NotifyMember;
use App\Console\Commands\UpdateDb;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(NotifyMember::class)->dailyAt('00:10');
Schedule::command(UpdateDb::class)->dailyAt('00:05');
