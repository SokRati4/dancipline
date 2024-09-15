<?php

namespace App\Jobs;

use App\Models\Competition;
use App\Models\User;
use App\Notifications\UpcomingCompetitionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class NotifyUpcomingCompetitions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Znajdź wszystkie turnieje, które odbędą się za tydzień
        $competitions = Competition::all();

        foreach ($competitions as $competition) {
            $user = User::find($competition->user_id);

            // Wyślij powiadomienie do użytkownika
            if ($user) {
                $user->notify(new UpcomingCompetitionNotification($competition));
            }
        }
    }
}
