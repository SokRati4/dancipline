<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Competition;
use Carbon\Carbon; 

class CompetitionController extends Controller
{

    public function schedule()
    {
        $now = Carbon::now();

        $startDate = $now->format('Y-m-d');

        $endDate = $now->copy()->addYear()->format('Y-m-d');

        $userId = Auth::id();

        $competitions = Competition::where('user_id', $userId)->get();
        $nextCompetition = Competition::where('user_id', $userId)
                                    ->where('start_date', '>=', $startDate)
                                    ->orderBy('start_date', 'asc')
                                    ->first();

        return view('competitions.schedule', compact('startDate', 'endDate', 'competitions','nextCompetition'));
    }

    public function createWithDate($date)
    {
        return view('competitions.create',compact('date'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'type' => 'required|in:regionalny,ogólnokrajowy,międzynarodowy,Mistrzowstwa Europy,Mistrzowstwa Świata,Blackpool Dance Festival,UK Open Dance Festival,International Dance Festival',
            'category' => 'required|in:u10,u12,u14,u16,u19,u21,amateur,professional',
        ]);

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

        return redirect()->route('competitions.schedule',compact('startDate', 'endDate', 'competitions','nextCompetition'))
            ->with('success', 'Konkurs został pomyślnie utworzony!');
    }

    public function show($id)
{
    $competition = Competition::findOrFail($id);

    return view('competitions.show', compact('competition'));
}

    public function edit($id)
    {
        $competition = Competition::findOrFail($id);
        return view('competitions.edit',compact('competition'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'location' => 'required|string|max:255',
        'type' => 'required|in:regionalny,ogólnokrajowy,międzynarodowy,Mistrzowstwa Europy,Mistrzowstwa Świata,Blackpool Dance Festival,UK Open Dance Festival,International Dance Festival',
        'category' => 'required|in:u10,u12,u14,u16,u19,u21,amateur,professional',
    ]);

    $competition = Competition::findOrFail($id);

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


    return redirect()->route('competitions.schedule',compact('startDate', 'endDate', 'competitions','nextCompetition'))
    ->with('success', 'Konkurs został pomyślnie utworzony!');
}

    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        $competition->delete();
        return response()->json(['success' => true]);
    }
}
