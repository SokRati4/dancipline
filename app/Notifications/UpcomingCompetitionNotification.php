<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingCompetitionNotification extends Notification
{
    use Queueable;

    private $competition;

    public function __construct($competition)
    {
        $this->competition = $competition;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your competition ' . $this->competition->name . ' is coming up in a week!',
            'competition_id' => $this->competition->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Your competition ' . $this->competition->name . ' is coming up in a week!',
            'competition_id' => $this->competition->id,
        ]);
    }
}
