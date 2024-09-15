@extends('layouts.app')
@section('content')
<h1>Archiwalne statystyki tygodniowe</h1>
@if ($archivedWeeklyStats)
    @foreach($archivedWeeklyStats as $weeklyStat)
    <h2>Statystyki tygodniowe z {{ \Carbon\Carbon::parse($weeklyStat->week_start_date)->translatedFormat('j F') }} po {{ \Carbon\Carbon::parse($weeklyStat->week_start_date)->addWeek()->translatedFormat('j F') }}</h2>
        <div class="weekly">
            <div id="totalMinutes"  >
                <h1>{{ $weeklyStat->total_training_minutes }}</h1>
                <p class="subtitle">minut zatańczono</p>
            </div>
            <div id="sessionsPercentage">
                <h1>{{ round($weeklyStat['completed_sessions_percentage']) }}%</h1>
                <p class="subtitle">sesji zakończono</p>
            </div>
            <div id="finalsDances">
                <h1>{{ $weeklyStat->finals_danced }}</h1>
                <p class="subtitle">piątek zatańczono</p>
            </div>
            <div id="daysTrained">
                <h1>{{ $weeklyStat->days_trained }}/7</h1>
                <p class="subtitle">dni treningowe w tym wygodniu</p>
            </div>
        </div>
    @endforeach
@endif
@endsection