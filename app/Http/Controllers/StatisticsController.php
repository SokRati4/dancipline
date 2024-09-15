<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrentSystemStat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingSession;
use App\Models\WeeklyStat;
use App\Models\TrainingSystem;
use Carbon\Carbon;
use App\Models\ArchivedWeeklyStat;
use App\Models\ArchivedSystemStat;

class StatisticsController extends Controller
{
    public function generateStats($userId, $trainingSystemId)
    {
        $sessions = TrainingSession::where('system_id', $trainingSystemId)->get();
        $totalSessions = $sessions->count();
        $completedSessions = $sessions->where('completed', 1)->count();
        $completedSessionsPercentage = $totalSessions > 0 ? ($completedSessions / $totalSessions) * 100 : 0;
        $totalTrainingHours = $sessions->sum('duration_hours');
        $completedTrainingHours = $totalSessions > 0 ?($sessions->where('completed', 1)->where('started',1)->sum('duration_hours')/$totalTrainingHours) * 100 : 0;
        $styleLaPercentage = $totalSessions > 0 ? ($sessions->where('style', 'LA')->count() / $totalSessions) * 100 : 0;
        $styleStPercentage = $totalSessions > 0 ? ($sessions->where('style', 'ST')->count() / $totalSessions) * 100 : 0;
        $styleSmoothPercentage = $totalSessions > 0 ? ($sessions->where('style', 'Smooth')->count() / $totalSessions) * 100 : 0;
        $trainingTypeStaminaPercentage = $totalSessions > 0 ? ($sessions->where('type', 'stamina')->count() / $totalSessions) * 100 : 0;
        $trainingTypeSelfPercentage = $totalSessions > 0 ? ($sessions->where('type', 'self')->count() / $totalSessions) * 100 : 0;
        $trainingTypeIndividualPercentage = $totalSessions > 0 ? ($sessions->where('type', 'individual')->count() / $totalSessions) * 100 : 0;
        $trainingTypeGroupPercentage = $totalSessions > 0 ? ($sessions->where('type', 'group')->count() / $totalSessions) * 100 : 0;

        // Oblicz średnią intensywność treningów
        $averageIntensity = $totalSessions > 0 ? $sessions->avg('intensity') : 0;

        $stats = CurrentSystemStat::updateOrCreate(
            [
                'user_id' => $userId,
                'training_system_id' => $trainingSystemId,
            ],
            [
                'completed_sessions' => $completedSessions,
                'total_sessions' => $totalSessions,
                'completed_sessions_percentage' => $completedSessionsPercentage,
                'total_training_hours' => $totalTrainingHours,
                'completed_training_hours' => $completedTrainingHours,
                'style_la_percentage' => $styleLaPercentage,
                'style_st_percentage' => $styleStPercentage,
                'style_smooth_percentage' => $styleSmoothPercentage,
                'training_type_stamina_percentage' => $trainingTypeStaminaPercentage,
                'training_type_self_percentage' => $trainingTypeSelfPercentage,
                'training_type_individual_percentage' => $trainingTypeIndividualPercentage,
                'average_intensity' => $averageIntensity, // Dodane pole
                'training_type_group_percentage' =>$trainingTypeGroupPercentage
            ]
        );

        return response()->json(['success' => true, 'stats' => $stats]);
    }
    public function generateWeekStats($userId, $systemId)
    {
        // Ustaw aktualny czas
        $now = Carbon::now();

        // Ustaw początek i koniec tygodnia
        $weekStart = $now->copy()->startOfWeek()->format('Y-m-d');
        $weekEnd = $now->copy()->endOfWeek()->addDay()->format('Y-m-d');

        // Pobierz sesje treningowe z tego tygodnia
        $sessions = TrainingSession::where('system_id', $systemId)
            ->whereBetween('start_datetime', [$weekStart, $weekEnd])
            ->get();

        // Oblicz statystyki tygodniowe
        $totalTrainingMinutes = ($sessions->where('completed', 1)->where('started', 1)->sum('duration_hours'))*60;

        $completedSessions = $sessions->where('completed', 1)->where('started', 1)->count();
        $totalSessions = $sessions->count();
        $completedSessionsPercentage = $totalSessions > 0 ? ($completedSessions / $totalSessions) * 100 : 0;

        $finalsDanced = $sessions->where('completed', 1)->where('started', 1)->where('five_dances', 1)->count();
        $daysTrained = $sessions->where('completed', 1)->where('started', 1)->groupBy(function($session) {
            return Carbon::parse($session->start_datetime)->format('Y-m-d');
        })->count();

        // Utwórz nowy obiekt WeeklyStat
        $weeklyStats = WeeklyStat::updateOrCreate(
            [
                'user_id' => $userId,
                'week_start_date' => $weekStart,
            ],
            [
                'total_training_minutes' => $totalTrainingMinutes,
                'completed_sessions_percentage' => $completedSessionsPercentage,
                'finals_danced' => $finalsDanced,
                'days_trained' => $daysTrained,
            ]
        );

        return response()->json([
            'success' => true, 'weeklyStats' => $weeklyStats
        ]);
    }
    // public function archiveWeeklyStats()
    // {
    //     $now = Carbon::now();

    //     // Sprawdzamy, czy aktualny dzień to poniedziałek
    //     // if ($now->dayOfWeek != Carbon::MONDAY) {
    //     //     return;
    //     // }

    //     $weekStart = $now->copy()->startOfWeek()->subWeek()->format('Y-m-d');
    //     $weekEnd = $now->copy()->endOfWeek()->subWeek()->format('Y-m-d');

    //     $users = User::all();

    //     foreach ($users as $user) {
    //         $weeklyStat = WeeklyStat::where('user_id', $user->id)
    //             ->whereBetween('start_datetime', [$weekStart, $weekEnd])
    //             ->get();

    //         if ($weeklyStat->total_training_minutes > 0) {


    //             ArchivedWeeklyStat::create([
    //                 'user_id' => $user->id,
    //                 'week_start_date' => $weeklyStat->week_start_date,
    //                 'total_training_minutes' => $weeklyStat->total_training_minutes,
    //                 'completed_sessions_percentage' => $weeklyStat->completed_sessions_percentage,
    //                 'finals_danced' => $weeklyStat->finals_danced,
    //                 'days_trained' => $weeklyStat->days_trained
    //             ]);
    //         }
    //     }
    // }
    public function indexArchivedWeeklyStats(){
        $userId = Auth::id();
        $archivedWeeklyStats = ArchivedWeeklyStat::where('user_id', $userId )
            ->get();
        return view('archived_weekly_stats.index', compact('archivedWeeklyStats'));
    }
    public function indexArchivedSystemStats(){
        $userId = Auth::id();
        $archivedSystems = ArchivedSystemStat::where('user_id', $userId )
            ->get();
        return view('archived_systems.index', compact('archivedSystems'));
    }
}
