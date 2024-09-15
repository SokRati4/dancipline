@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Szczegóły turnieju</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Turniej: {{ $competition->name }}</h5>
                <p><strong>Data:</strong> {{ $competition->start_date }}</p>
                <p><strong>Miejsce:</strong> {{ $competition->location }}</p>
                <p><strong>Kategoria:</strong> {{ $competition->category }}</p>
                <p><strong>Ile zostało dni:</strong> {{ floor(now()->diffInDays($competition->start_date)) }} dni</p>
            </div>
        </div>

        <a href="{{ route('competitions.schedule') }}" class="btn btn-primary mt-3">Powrót do harmonogramu</a>
    </div>
@endsection