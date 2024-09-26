@extends('layouts.app')
@section('custom_styles')
<style>
    .row {
    display: flex;
    flex-wrap: wrap; 
    gap: 20px; 
    justify-content: space-between; 
}

.notes-item {
    flex: 0 1 calc(33.333% - 20px); 
    box-sizing: border-box; 
    overflow: hidden; 
    border: 1px solid #ddd; 
    border-radius: 8px; 
    background-color: #2a2a2a;
    display: flex;
    flex-direction: column;
    padding: 15px;
    color: #ffffff; 
}

.notes-item h3 {
    font-size: 18px; 
    margin: 0 0 10px 0; /
}



@media (max-width: 768px) {
    .education-item {
        flex: 1 1 100%; 
        width: auto; 
    }
}
    
</style>
@endsection
@section('title', 'Home')
@section('content')
<div class="container">
    <h1>Twoje Sesje Treningowe</h1>

    @foreach ($sessions as $systemId => $groupedSessions)
        @php
            $system = $groupedSessions->first()->trainingSystem; 
        @endphp

        <h2>{{ $system->name }}</h2>
        <div class="row">
            @foreach ($groupedSessions as $session)
                <div class="notes-item">
                    @if ($session->note_id)
                        <a href="{{ route('notes.show', $session->note_id) }}"><h3>{{$session->type}} trening {{ \Carbon\Carbon::parse($session->start_datetime)->translatedFormat('j F') }}</h3></a>
                        <p><strong>Czas trwania: </strong>{{ $session->duration_hours }} godzin</p>
                        <p><strong>Styl: </strong>{{ $session->style }}</p>
                        <a href="{{ route('notes.edit', $session->note_id) }}" class="btn btn-warning"><button class="button-27" >Edytuj notatkę</button></a>
                        <form action="{{ route('notes.destroy', $session->note_id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-27">Usuń Notatkę</button>
                        </form>
                        @else
                        <h3>{{$session->type}} training on {{ \Carbon\Carbon::parse($session->start_datetime)->translatedFormat('j F') }}</h3>
                        <p><strong>Czas trwania: </strong>{{ $session->duration_hours }} godzin</p>
                        <p><strong>Styl: </strong>{{ $session->style }}</p>
                        <a href="{{ route('notes.create', ['session_id' => $session->id]) }}" class="btn btn-primary"><button class="button-27" type="submit">Dodaj Notatkę</button></a>
                        @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
