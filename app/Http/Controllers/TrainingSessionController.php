<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingSystem;
use App\Http\Controllers\TrainingSystemController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrainingSessionController extends Controller
{

    public function index()
    {
        // Pobierz wszystkie sesje treningowe (przykładowo)
        $trainingSessions = TrainingSession::all();

        // Zwróć widok z danymi
        return view('training_sessions.index', compact('trainingSessions'));
    }


    public function create()
    {
        // Zwróć widok formularza do tworzenia nowej sesji treningowej
        return view('training_sessions.create');
    }


    public function store(Request $request)
    {
        

        $systemId = $request->system_id;
        // Walidacja danych wejściowych
        $request->validate([
            'type' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'style' => 'required|string|max:255',
            'dances_planned' => 'required|array',
            'five_dances' => 'sometimes|string|in:0,1',
            'with_partner' => 'sometimes|string|in:0,1',
        ]);

        $startDatetime = Carbon::parse($request->start_datetime);
        $endDatetime = Carbon::parse($request->end_datetime);
        $durationHours = $startDatetime->diffInMinutes($endDatetime) / 60;

        $intensity = $this->calculateIntensity($request->type, $request->style, $durationHours, $request->has('five_dances'), $request->has('with_partner'));

        $userId = Auth::id();

        $session = new TrainingSession();
        $session->user_id = $userId;
        $session->system_id = $systemId;
        $session->type = $request->type;
        $session->start_datetime = $request->start_datetime;
        $session->end_datetime = $request->end_datetime;
        $session->duration_hours = number_format($durationHours, 2);
        $session->intensity = $intensity;
        $session->style = $request->style;
        $session->dances_planned = implode(',', $request->dances_planned);
        $session->five_dances = $request->has('five_dances') ? (int)$request->five_dances : 0;
        $session->with_partner = $request->has('with_partner') ? (int)$request->with_partner : 0;
    
        // Utwórz nową sesję treningową na podstawie danych z formularza
        $session->save();

        app(TrainingSystemController::class)->updateCompletionPercentage($session->system_id);
        app(StatisticsController::class)->generateStats(Auth::id(), $session->system_id);
        app(StatisticsController::class)->generateWeekStats(Auth::id(), $session->system_id);
    
        // Przekieruj na widok kalendarza
        return redirect()->route('training_systems.schedule', ['trainingSystem' => $request->system_id])
            ->with('success', 'Sesja treningowa została pomyślnie dodana.');
    }


    public function show($id)
    {
        // Znajdź sesję treningową po ID
        $trainingSession = TrainingSession::findOrFail($id);

        // Zwróć widok szczegółowy sesji treningowej
        return view('training_sessions.show', compact('trainingSession'));
    }


    public function edit($id, $system_id)
    {
        // Znajdź sesję treningową po ID
        $trainingSession = TrainingSession::findOrFail($id);
        $trainingSystem = TrainingSystem::findOrFail($system_id);
        $systemStyle = $trainingSystem->dance_style;

        $trainingSession->start_datetime = Carbon::parse($trainingSession->start_datetime)->format('Y-m-d\TH:i');
        $trainingSession->end_datetime = Carbon::parse($trainingSession->end_datetime)->format('Y-m-d\TH:i');

        // Zwróć widok formularza edycji sesji treningowej
        return view('training_sessions.edit', compact('trainingSession','system_id','systemStyle'));
    }

    public function update(Request $request, TrainingSession $trainingSession)
{
    $system_id = $request->system_id;

    $validated = $request->validate([
        'type' => 'required|string',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date',
        'style' => 'required|string',
        'dances_planned' => 'required|array',
        'five_dances' => 'sometimes|string|in:0,1',
        'with_partner' => 'sometimes|string|in:0,1',
    ]);

    $startDatetime = Carbon::parse($request->start_datetime);
    $endDatetime = Carbon::parse($request->end_datetime);
    $durationHours = $startDatetime->diffInMinutes($endDatetime) / 60.0;

    $intensity = $this->calculateIntensity($request->type, $request->style, $durationHours, $request->has('five_dances'), $request->has('with_partner'));

    $validated['dances_planned'] = implode(',', $request->dances_planned);

    $trainingSession->update(array_merge($validated, [
        'user_id' => Auth::id(),
        'duration_hours' => number_format($durationHours, 2), 
        'intensity' => $intensity
    ]));
    app(TrainingSystemController::class)->updateCompletionPercentage($trainingSession->system_id);
    app(StatisticsController::class)->generateStats(Auth::id(), $trainingSession->system_id);
    app(StatisticsController::class)->generateWeekStats(Auth::id(), $trainingSession->system_id);


    // Przekieruj na widok kalendarza
    return redirect()->route('training_systems.schedule', ['trainingSystem' => $system_id])
        ->with('success', 'Sesja treningowa została pomyślnie zaktualizowana.');
}



    public function destroy($id)
    {
        // Znajdź sesję treningową po ID i usuń ją
        $trainingSession = TrainingSession::findOrFail($id);
        $system_id = $trainingSession->system_id;
        $trainingSession->delete();
        app(TrainingSystemController::class)->updateCompletionPercentage($system_id);
        app(StatisticsController::class)->generateStats(Auth::id(), $system_id);
        app(StatisticsController::class)->generateWeekStats(Auth::id(), $system_id);

        // Przekieruj na widok lub zwróć odpowiedź
        return response()->json(['success' => true]);
    }

    public function createWithDate($date, $systemId)
    {
        $trainingSystem = TrainingSystem::findOrFail($systemId);
        $systemStyle = $trainingSystem->dance_style;
        

        return view('training_sessions.create', compact('date', 'systemId','systemStyle'));
    }
    public function startSession(Request $request, $id)
    {
        $session = TrainingSession::findOrFail($id);
        $session->started = 1;
        $session->start_confirmed_at = $request->input('start_confirmed_at');
        $session->save();

        app(TrainingSystemController::class)->updateCompletionPercentage($session->system_id);
        app(StatisticsController::class)->generateStats(Auth::id(), $session->system_id);
        app(StatisticsController::class)->generateWeekStats(Auth::id(), $session->system_id);

        return response()->json(['success' => true]);
    }
    public function finishSession(Request $request, $id)
    {
        $session = TrainingSession::findOrFail($id);
        $session->completed = 1;
        $session->end_confirmed_at = $request->input('end_confirmed_at');
        $session->save();

        app(TrainingSystemController::class)->updateCompletionPercentage($session->system_id);
        app(StatisticsController::class)->generateStats(Auth::id(), $session->system_id);
        app(StatisticsController::class)->generateWeekStats(Auth::id(), $session->system_id);

        return response()->json(['success' => true]);
    }
    
    private function calculateIntensity($type, $style, $durationHours, $fiveDances, $withPartner)
    {
    $intensity = 0;

    // Typ treningu
    switch ($type) {
        case 'individual':
            $intensity += 2;
            break;
        case 'group':
            $intensity += 3;
            break;
        case 'stamina':
            $intensity += 5;
            break;
        case 'self':
            $intensity += 1;
            break;
    }

    // Styl sesji
    switch ($style) {
        case 'ST':
        case 'LA':
            $intensity += 2;
            break;
        case '10 dances':
            $intensity += 3;
            break;
        case 'Smooth':
            $intensity += 2;
            break;
    }

    // Czas trwania sesji
    if ($durationHours >= 1 && $durationHours < 2) {
        $intensity += 1;
    } elseif ($durationHours >= 2 && $durationHours < 3) {
        $intensity += 2;
    } elseif ($durationHours >= 3 && $durationHours < 4) {
        $intensity += 3;
    } elseif ($durationHours >= 4) {
        $intensity += 5;
    }

    // Czynnik "five_dances"
    if ($fiveDances) {
        $intensity += 3;
    }

    // Czynnik "with_partner"
    if ($withPartner) {
        $intensity += 2;
    }

    // Normalizacja intensywności
    $maxintensity = 18;
    $intensity = ($intensity/$maxintensity)*10;
    return min($intensity, 10);
    }
}
