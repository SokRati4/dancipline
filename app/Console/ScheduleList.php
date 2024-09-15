<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

class ScheduleList
{
    public static function register(Schedule $schedule)
    {
        // Dodaj zadania do harmonogramu
        $schedule->command('stats:archive')->everyMinute();
    }
}
