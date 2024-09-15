<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Competition;
use Carbon\Carbon; 

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function schedule()
    {
        // Ustal startDate jako aktualną datę
        $now = Carbon::now();

        $startDate = $now->format('Y-m-d');

        // Ustal endDate jako startDate powiększoną o 1 rok
        $endDate = $now->copy()->addYear()->format('Y-m-d');

        // Pobierz user_id zalogowanego użytkownika
        $userId = Auth::id();

        // Pobierz wszystkie obiekty Competition, gdzie user_id równa się zalogowanemu userId
        $competitions = Competition::where('user_id', $userId)->get();
        $nextCompetition = Competition::where('user_id', $userId)
                                    ->where('start_date', '>=', $startDate)
                                    ->orderBy('start_date', 'asc')
                                    ->first();

        // Możesz teraz użyć zmiennych w swojej logice lub przekazać je do widoku
        return view('competitions.schedule', compact('startDate', 'endDate', 'competitions','nextCompetition'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createWithDate($date)
    {
        return view('competitions.create',compact('date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Walidacja danych wejściowych
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'type' => 'required|in:regionalny,ogólnokrajowy,międzynarodowy,Mistrzowstwa Europy,Mistrzowstwa Świata,Blackpool Dance Festival,UK Open Dance Festival,International Dance Festival',
            'category' => 'required|in:u10,u12,u14,u16,u19,u21,amateur,professional',
        ]);

        // Tworzenie nowego rekordu
        $competition = new Competition();
        $competition->user_id = $validated['user_id'];
        $competition->name = $validated['name'];
        $competition->start_date = $validated['start_date'];
        $competition->end_date = $validated['end_date'];
        $competition->location = $validated['location'];
        $competition->type = $validated['type'];
        $competition->category = $validated['category'];
        $competition->save();

        $now = Carbon::now();
        $startDate = $now->format('Y-m-d');
        $endDate = $now->copy()->addYear()->format('Y-m-d');
        $userId = Auth::id();
        $competitions = Competition::where('user_id', $userId)->get();
        $nextCompetition = Competition::where('user_id', $userId)
                                    ->where('start_date', '>=', $startDate)
                                    ->orderBy('start_date', 'asc')
                                    ->first();

        // Przekierowanie po pomyślnym zapisaniu
        return redirect()->route('competitions.schedule',compact('startDate', 'endDate', 'competitions','nextCompetition'))
            ->with('success', 'Konkurs został pomyślnie utworzony!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Pobranie rekordu turnieju
    $competition = Competition::findOrFail($id);

    // Przekazanie danych do widoku
    return view('competitions.show', compact('competition'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $competition = Competition::findOrFail($id);
        return view('competitions.edit',compact('competition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Walidacja danych wejściowych
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'location' => 'required|string|max:255',
        'type' => 'required|in:regionalny,ogólnokrajowy,międzynarodowy,Mistrzowstwa Europy,Mistrzowstwa Świata,Blackpool Dance Festival,UK Open Dance Festival,International Dance Festival',
        'category' => 'required|in:u10,u12,u14,u16,u19,u21,amateur,professional',
    ]);

    // Znajdź rekord do aktualizacji
    $competition = Competition::findOrFail($id);

    // Zaktualizuj rekord
    $competition->update($validated);
    $now = Carbon::now();
    $startDate = $now->format('Y-m-d');
    $endDate = $now->copy()->addYear()->format('Y-m-d');
    $userId = Auth::id();
    $competitions = Competition::where('user_id', $userId)->get();
    $nextCompetition = Competition::where('user_id', $userId)
                                    ->where('start_date', '>=', $startDate)
                                    ->orderBy('start_date', 'asc')
                                    ->first();


    // Przekierowanie po aktualizacji
    return redirect()->route('competitions.schedule',compact('startDate', 'endDate', 'competitions','nextCompetition'))
    ->with('success', 'Konkurs został pomyślnie utworzony!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();
        return response()->json(['success' => true]);
    }
}
