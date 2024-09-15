<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TrainingSystem;
use App\Models\WeeklyStat;
use App\Models\CurrentSystemStat;
use App\Models\ArchivedSystemStat;
use App\Models\ArchivedWeeklyStat;
use Carbon\Carbon;

class ArchiveStatsCommand extends Command
{
    protected $signature = 'stats:archive';
    protected $description = 'Archiwizuje statystyki systemu i tygodnia.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        // Archiwizacja systemów, które przestały być aktualne
        $trainingSystems = TrainingSystem::where('end_date', '<', $now->startOfDay())->get();
        foreach ($trainingSystems as $system) {
            $this->archiveSystemStats($system);
        }

        // Sprawdzenie czy dzisiaj jest poniedziałek
        // if ($now->isMonday()) {
            $weekEnd = $now->copy()->subSecond()->startOfWeek(); // Koniec poprzedniego tygodnia (niedziela, 23:59:59)
            $weekStart = $weekEnd->copy()->subWeek()->startOfWeek(); // Początek poprzedniego tygodnia (poniedziałek, 00:00:00)

            $users = User::all();
            foreach ($users as $user) {
                $this->archiveWeeklyStats($user->id, $weekStart, $weekEnd);
            }
        //}

        $this->info('Statystyki zostały zarchiwizowane.');
    }

    protected function archiveSystemStats($system)
    {
        $currentStats = CurrentSystemStat::where('training_system_id', $system->id)->first();
        if ($currentStats) {
            ArchivedSystemStat::create([
                'user_id' => $currentStats->user_id,
                'training_system_id' => $system->id,
                'start_date' => $system->start_date,
                'end_date' => $system->end_date,
                'completed_sessions' => $currentStats->completed_sessions,
                'total_sessions' => $currentStats->total_sessions,
                'completed_sessions_percentage' => $currentStats->completed_sessions_percentage,
                'total_training_hours' => $currentStats->total_training_hours,
                'style_la_percentage' => $currentStats->style_la_percentage,
                'style_st_percentage' => $currentStats->style_st_percentage,
                'style_smooth_percentage' => $currentStats->style_smooth_percentage,
                'training_type_stamina_percentage' => $currentStats->training_type_stamina_percentage,
                'training_type_self_percentage' => $currentStats->training_type_self_percentage,
                'training_type_individual_percentage' => $currentStats->training_type_individual_percentage,
            ]);
            $currentStats->delete();
        }
    }

    protected function archiveWeeklyStats($userId, $weekStart, $weekEnd)
    {
        $weeklyStat = WeeklyStat::where('user_id', $userId)
            ->whereBetween('week_start_date', [$weekStart, $weekEnd])
            ->first();

        if ($weeklyStat) {
            ArchivedWeeklyStat::create([
                'user_id' => $weeklyStat->user_id,
                'week_start_date' => $weeklyStat->week_start_date,
                'total_training_minutes' => $weeklyStat->total_training_minutes,
                'completed_sessions_percentage' => $weeklyStat->completed_sessions_percentage,
                'finals_danced' => $weeklyStat->finals_danced,
                'days_trained' => $weeklyStat->days_trained,
            ]);
            $weeklyStat->delete();
        }
    }
}
