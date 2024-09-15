@extends('layouts.app')
@section('custom_styles')
<style>
    .card-body p {
    font-size: 1.2em; /* 2 razy większy niż domyślny rozmiar czcionki */
    }
</style>
@endsection
@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ $tutorial->title }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body">
                    <p><strong>Opis:</strong> {{ $tutorial->description }}</p>
                    <p><strong>Czas trwania:</strong> {{ $tutorial->duration }}</p>
                    <p><strong>Poziom:</strong> {{ ucfirst($tutorial->level) }}</p>
                    <a href="{{ $tutorial->url }}" class="btn btn-success"><button class="button-27" type="submit">Oglądaj</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
