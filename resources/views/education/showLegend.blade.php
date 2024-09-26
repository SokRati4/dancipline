@extends('layouts.app')
@section('custom_styles')
<style>
    .card-body p {
        font-size: 1.2em; /
    }
    ul.best-results {
        list-style-type: disc; 
        padding-left: 20px;
        font-size: 1.2em; 
    }
    ul.best-results li {
        margin-bottom: 5px; 
    }
    .known-for {
        margin-top: 20px;
        text-align: center; 
    }
    h4 {
        font-size: 1.4em;
        margin-bottom: 10px;
    }
    .badge-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        gap: 20px; 
    }
    .badge {
        display: inline-block;
        padding: 0.4em 0.6em;
        font-size: 1.1em;
        font-weight: 600;
        color: #fff;
        background-color: #1f1f1f;
        border-radius: 12px;
        margin-bottom: 5px;
    }
</style>
@endsection
@section('title', 'Title of the Page')
@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ $legend->partner1_name }} i {{ $legend->partner2_name }}</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body">
                    <p>{{ $legend->bio }}</p>
                    @php
                        $results = explode('.', $legend->best_results); 
                        $knownForItems = explode(',', $legend->known_for); 
                    @endphp
                                        @if (!empty($legend->known_for))
                                        <div class="known-for">
                                            <h4>Znane z:</h4>
                                            <div class="badge-container">
                                                @foreach ($knownForItems as $item)
                                                    @if (!empty(trim($item)))
                                                        <span class="badge">{{ trim($item) }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                    <!-- Wyświetlenie wyników jako lista -->
                    @if (!empty($results))
                        <h4>Najlepsze wyniki:</h4>
                        <ul class="best-results">
                            @foreach ($results as $result)
                                @if (!empty(trim($result))) <!-- Sprawdzenie, czy element nie jest pusty -->
                                    <li>{{ trim($result) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ $legend->videos }}" class="btn btn-success"><button class="button-27" type="submit">Oglądaj</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
