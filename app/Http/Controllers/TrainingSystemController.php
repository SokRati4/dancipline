<?php

namespace App\Http\Controllers;

use App\Models\CurrentSystemStat;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingSystem;
use App\Models\TrainingSession;
use App\Models\WeeklyStat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use CurlHandle;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Support\Facades\Session;

class TrainingSystemController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        //dd("Current time: ", $now->toDateTimeString());
        if (Session::has('timezone')) {
            $now->setTimezone(Session::get('timezone'));
        }
        // Pobierz aktualny system treningowy użytkownika
        $currentSystem = TrainingSystem::where('user_id', $userId)
            ->where('start_date', '<=', $now)
            ->where(function($query) use ($now) {
                $query->where('end_date', '>=', $now)
                      ->orWhereNull('end_date');
            })
            ->first();
        //dd("Current system: ", $currentSystem);
        $trainingSessions = [];
        $stats = null;
        $weeklyStats = null;
        $weekStart = $now->copy()->startOfWeek()->format('Y-m-d');
        $weekEnd = $now->copy()->endOfWeek()->addDay()->format('Y-m-d');
        if ($currentSystem) {
            $trainingSessions = TrainingSession::where('system_id', $currentSystem->id)->get();
            $stats = CurrentSystemStat::where('user_id',$userId)
                ->where('training_system_id', $currentSystem->id)
                ->first();
            $weeklyStats = WeeklyStat::where('user_id', $userId)
                ->where('week_start_date', $weekStart)
                ->first();
        }
        $stats = [
            'completed_sessions_percentage' => $stats->completed_sessions_percentage ?? 0,
            'total_training_hours' => $stats->total_training_hours ?? 0,
            'completed_training_hours' => $stats->completed_training_hours ?? 0,
            'style_la_percentage' => $stats->style_la_percentage ?? 0,
            'style_st_percentage' => $stats->style_st_percentage ?? 0,
            'style_smooth_percentage' => $stats->style_smooth_percentage ?? 0,
            'training_type_stamina_percentage' => $stats->training_type_stamina_percentage ?? 0,
            'training_type_self_percentage' => $stats->training_type_self_percentage ?? 0,
            'training_type_individual_percentage' => $stats->training_type_individual_percentage ?? 0,
            'average_intensity' => $stats->average_intensity ?? 0,
            'training_type_group_percentage' => $stats->training_type_group_percentage ?? 0,
        ];
        $weeklyStats = [
                'total_training_minutes' => $weeklyStats->total_training_minutes ?? 0,
                'completed_sessions_percentage' => $weeklyStats->completed_sessions_percentage ?? 0,
                'finals_danced' => $weeklyStats->finals_danced ?? 0,
                'days_trained' => $weeklyStats->days_trained ?? 0,
        ];
        return view('training_systems.index', ['currentSystem' => $currentSystem,'trainingSessions' => $trainingSessions,'stats' => $stats, 'weeklyStats' => $weeklyStats,'weekStart' => $weekStart, 'weekEnd' => $weekEnd]);
    }
    


    public function create()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // Sprawdź, czy użytkownik ma już aktywny system treningowy
        $hasActiveSystem = TrainingSystem::where('user_id', $userId)
            ->where('start_date', '<=', $now)
            ->where(function($query) use ($now) {
                $query->where('end_date', '>=', $now)
                      ->orWhereNull('end_date');
            })
            ->exists();

        // Jeśli użytkownik ma aktywny system, przekieruj z komunikatem
        if ($hasActiveSystem) {
            return redirect()->route('training_systems.index')->with('error', 'You already have an active training system.');
        }

        return view('training_systems.create');
    }


    public function store(Request $request)
    {
        $today = Carbon::now()->startOfDay();
         // Walidacja danych
         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:'.$today,
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $today->copy()->addYear()->toDateString(),
            'system_type' => 'required|in:vacation,pre-tournament,dance_camp,off-season',
            'dance_style' => 'required|in:ST,LA,10 dances,Smooth',
        ]);

        // Dodaj user_id do danych wejściowych
        $userID = Auth::id();

        // Utwórz nowy system treningowy
        $system = new TrainingSystem();
        $system->user_id = $userID;
        $system->name = $request->name;
        $system->description = $request->description;
        $system->start_date = $request->start_date;
        $system->end_date = $request->end_date;
        $system->system_type = $request->system_type;
        $system->dance_style = $request->dance_style;
        $system->save();



        return redirect()->route('training_systems.schedule', ['trainingSystem' => $system->id])
        ->with('success', 'Training system created successfully.');
    }


    public function show(TrainingSystem $trainingSystem)
    {
        // Upewnij się, że użytkownik ma dostęp do systemu treningowego
        $this->authorize('view', $trainingSystem);

        return view('training_systems.show', compact('trainingSystem'));
    }

    public function edit(TrainingSystem $trainingSystem)
    {
        // Upewnij się, że użytkownik ma dostęp do systemu treningowego
        $this->authorize('update', $trainingSystem);

        return view('training_systems.edit', compact('trainingSystem'));
    }


    public function update(Request $request, TrainingSystem $trainingSystem)
    {
        //dd($request->all());
        // Upewnij się, że użytkownik ma dostęp do systemu treningowego
        $this->authorize('update', $trainingSystem);
        $today = Carbon::now()->startOfDay();

        // Walidacja danych
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . $today->copy()->addYear()->toDateString(),
            'system_type' => 'required|in:vacation,pre-tournament,dance_camp,off-season',
            'dance_style' => 'required|in:ST,LA,10 dances,Smooth',
        ]);
        

        // Aktualizacja danych systemu treningowego
        $trainingSystem->update($validated);

        return redirect()->route('training_systems.index')->with('success', 'Training system updated successfully.');
    }


    public function destroy(TrainingSystem $trainingSystem)
    {
        // Upewnij się, że użytkownik ma dostęp do systemu treningowego
        $this->authorize('delete', $trainingSystem);

        //$trainingSystem->trainingSessions()->delete();
        $trainingSessions = TrainingSession::where('system_id', $trainingSystem->id)->get();

        // Usuń wszystkie sesje powiązane z systemem
        foreach ($trainingSessions as $session) {
            $session->delete();
        }
        $currentsystemstat = CurrentSystemStat::where('training_system_id', $trainingSystem->id)->first();
        // Usuń system treningowy
        $currentsystemstat->delete();
        $trainingSystem->delete();

        return redirect()->route('training_systems.index')->with('success', 'System treningowy oraz wszystkie powiązane sesje zostały pomyślnie usunięte.');
    }
    public function schedule(TrainingSystem $trainingSystem)
{
    // Upewnij się, że użytkownik ma dostęp do systemu treningowego
    $this->authorize('view', $trainingSystem);

    // Pobierz daty początkową i końcową systemu treningowego
    $startDate = $trainingSystem->start_date;
    $endDate = $trainingSystem->end_date;

    // Pobierz wszystkie sesje treningowe dla tego systemu
    $trainingSessions = TrainingSession::where('system_id', $trainingSystem->id)->get();

    return view('training_systems.schedule', compact('trainingSystem', 'startDate', 'endDate', 'trainingSessions'));
}
public function updateCompletionPercentage($systemId)
    {
        $system = TrainingSystem::findOrFail($systemId);
        $totalSessions = TrainingSession::where('system_id', $systemId)->count();
        $completedSessions = TrainingSession::where('system_id', $systemId)
                                ->where('started', 1)
                                ->where('completed', 1)
                                ->count();

        if ($totalSessions > 0) {
            $system->completion_percentage = ($completedSessions / $totalSessions) * 100;
        } else {
            $system->completion_percentage = 0;
        }

        $system->save();
    }

}