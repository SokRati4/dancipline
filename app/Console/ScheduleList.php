<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

class ScheduleList
{
    public static function register(Schedule $schedule)
    {
        $schedule->command('stats:archive')->everyMinute();
    }
}
