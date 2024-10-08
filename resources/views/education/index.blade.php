@extends('layouts.app')


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
    flex-wrap: wrap; 
    gap: 20px; 
    justify-content: space-between; 
}

.education-item {
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

.education-item h3 {
    font-size: 18px;
    margin: 0 0 10px 0; 
}

.education-item p {
    display: -webkit-box; 
    -webkit-line-clamp: 3; 
    -webkit-box-orient: vertical; 
    overflow: hidden; 
    text-overflow: ellipsis; 
    margin: 0 0 10px 0; 
    line-height: 1.5; 
    height: 4.5em;

}


@media (max-width: 768px) {
    .education-item {
        flex: 1 1 100%; 
        width: auto; 
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

