@extends('layouts.app')

@section('title', 'Education')

@section('custom_styles')
<style>
    .filter-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    .filter-container select {
        padding: 5px;
        font-size: 16px;
    }
    .row {
    display: flex;
    flex-wrap: wrap; /* Umożliwia zawijanie kart do nowej linii */
    gap: 20px; /* Dystans pomiędzy kartami */
    justify-content: space-between; /* Rozkłada karty równomiernie */
}

.education-item {
    flex: 0 1 calc(33.333% - 20px); /* Trzy kartki w linii z uwzględnieniem odstępów */
    box-sizing: border-box; /* Upewnij się, że padding i border są uwzględniane w szerokości */
    overflow: hidden; /* Ukryj zawartość, która wykracza poza kartę */
    border: 1px solid #ddd; /* Dodaj obramowanie */
    border-radius: 8px; /* Zaokrąglenie rogów */
    background-color: #2a2a2a; /* Tło kartki */
    display: flex;
    flex-direction: column;
    padding: 15px;
    color: #ffffff; /* Padding wewnętrzny */
}

.education-item h3 {
    font-size: 18px; /* Rozmiar czcionki tytułu */
    margin: 0 0 10px 0; /* Odstęp od opisu */
}

.education-item p {
    display: -webkit-box; /* Używa flexbox w WebKit do kontrolowania układu */
    -webkit-line-clamp: 3; /* Ogranicza tekst do 5 linii */
    -webkit-box-orient: vertical; /* Ustawia orientację flexboxu na pionową */
    overflow: hidden; /* Ukrywa tekst, który wykracza poza kontener */
    text-overflow: ellipsis; /* Dodaje „...” na końcu tekstu */
    margin: 0 0 10px 0; /* Odstęp od przycisku */
    line-height: 1.5; /* Wysokość linii */
    height: 4.5em;

}


@media (max-width: 768px) {
    .education-item {
        flex: 1 1 100%; /* Na mniejszych ekranach, karty zajmują pełną szerokość */
        width: auto; /* Usuń stałą szerokość */
    }
}
    
</style>
@endsection

@section('content')
<div class="filter-container">
    <form action="{{ route('education.index') }}" method="GET">
        <select name="filter" onchange="this.form.submit()">
            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Pokaż wszystko</option>
            <option value="tutorials" {{ $filter == 'tutorials' ? 'selected' : '' }}>Pokaż tutoriale</option>
            <option value="legends" {{ $filter == 'legends' ? 'selected' : '' }}>Pokaż legendy taneczne</option>
        </select>
    </form>
</div>

<div class="row">
    @foreach ($tutorials as $tutorial)
        <div class="education-item">
            <a href="{{route('educationTutorial.show',$tutorial->id)}}"><h3>{{ $tutorial->title }}</h3></a>
            <p>{{ $tutorial->description }}</p>
            <a href="{{ $tutorial->url }}"><button class="button-27" type="submit">Oglądaj tutorial</button></a>
        </div>
    @endforeach

    @foreach ($dance_legends as $legend)
        <div class="education-item">
            <a href="{{ route('educationLegend.show',$legend->id) }}"><h3>{{ $legend->partner1_name }} & {{ $legend->partner2_name }}</h3></a>
            <p>{{ $legend->bio }}</p>
            <a href="{{ $legend->videos }}"><button class="button-27" type="submit">Oglądaj występ</button></a>
        </div>
    @endforeach
</div>
@endsection

