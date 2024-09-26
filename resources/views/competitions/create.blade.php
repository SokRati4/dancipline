@extends('layouts.app')

@section('custom_styles')
<style>
    .form-container * {
        box-sizing: border-box;
    }

    .form-container {
        background-color: #2a2a2a;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        overflow: visible; 
    }


    .form-container label {
        display: block;
        color: #ffffff;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .form-container input[type="text"],
    .form-container input[type="date"],
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #444;
        border-radius: 4px;
        background-color: #333;
        color: #ffffff;
        font-size: 14px;
    }

    .form-container input[type="text"]:focus,
    .form-container input[type="date"]:focus,
    .form-container select:focus {
        border-color: #58a6ff;
        outline: none;
    }

    .form-container h1 {
        text-align: center;
        color: #ffffff;
        margin-bottom: 20px;
    }

</style>
@endsection

@section('content')
<div class="form-container">
    <h1>Utwórz nowy zawod</h1>

    <form action="{{ route('competitions.store') }}" method="POST">
        @csrf 

        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <label for="start_date">Data rozpoczęcia:</label>
        <input type="date" id="start_date" name="start_date" value="{{ $date }}" required>

        <label for="end_date">Data zakończenia:</label>
        <input type="date" id="end_date" name="end_date" required>

        <label for="name">Nazwa:</label>
        <input type="text" id="name" name="name" required>

        <label for="location">Miejsce:</label>
        <input type="text" id="location" name="location" required>

        <label for="type">Typ:</label>
        <select id="type" name="type" required>
            <option value="regionalny">Regionalny</option>
            <option value="ogólnokrajowy">Ogólnokrajowy</option>
            <option value="międzynarodowy">Międzynarodowy</option>
            <option value="Mistrzowstwa Europy">Mistrzostwa Europy</option>
            <option value="Mistrzowstwa Świata">Mistrzostwa Świata</option>
            <option value="Blackpool Dance Festival">Blackpool Dance Festival</option>
            <option value="UK Open Dance Festival">UK Open Dance Festival</option>
            <option value="International Dance Festival">International Dance Festival</option>
        </select>

        <label for="category">Kategoria:</label>
        <select id="category" name="category" required>
            <option value="u10">U10</option>
            <option value="u12">U12</option>
            <option value="u14">U14</option>
            <option value="u16">U16</option>
            <option value="u19">U19</option>
            <option value="u21">U21</option>
            <option value="amateur">Amateur</option>
            <option value="professional">Professional</option>
        </select>

        <button type="submit" class="button-27">Zapisz</button>
    </form>
</div>
@endsection