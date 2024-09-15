@extends('layouts.app')

@section('custom_styles')
<style>
    .form-container * {
        box-sizing: border-box;
    }

    /* Form Container */
    .form-container {
        background-color: #2a2a2a;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        overflow: visible; /* Upewnij się, że wszystko w kontenerze jest widoczne */
    }

    /* Form Elements */
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

    /* Form Heading */
    .form-container h1 {
        text-align: center;
        color: #ffffff;
        margin-bottom: 20px;
    }

</style>
@endsection

@section('content')
<div class="form-container">
    <h1>Edytuj zawod</h1>

    <!-- Wyświetlanie komunikatów o błędach -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Wyświetlanie komunikatu o sukcesie -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('competitions.update', $competition->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Metoda PUT dla aktualizacji -->

        <!-- Pole dla user_id (ukryte) -->
        <input type="hidden" name="user_id" value="{{ $competition->user_id }}">

        <!-- Pole dla start_date -->
        <label for="start_date">Data rozpoczęcia:</label>
        <input type="date" id="start_date" name="start_date" value="{{ $competition->start_date }}" required>

        <!-- Pole dla end_date -->
        <label for="end_date">Data zakończenia:</label>
        <input type="date" id="end_date" name="end_date" value="{{ $competition->end_date }}" required>

        <!-- Pole dla name -->
        <label for="name">Nazwa:</label>
        <input type="text" id="name" name="name" value="{{ $competition->name }}" required>

        <!-- Pole dla location -->
        <label for="location">Miejsce:</label>
        <input type="text" id="location" name="location" value="{{ $competition->location }}" required>

        <!-- Pole dla type -->
        <label for="type">Typ:</label>
        <select id="type" name="type" required>
            @foreach(['regionalny', 'ogólnokrajowy', 'międzynarodowy', 'Mistrzowstwa Europy', 'Mistrzowstwa Świata', 'Blackpool Dance Festival', 'UK Open Dance Festival', 'International Dance Festival'] as $type)
                <option value="{{ $type }}" {{ $competition->type == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>

        <!-- Pole dla category -->
        <label for="category">Kategoria:</label>
        <select id="category" name="category" required>
            @foreach(['u10', 'u12', 'u14', 'u16', 'u19', 'u21', 'amateur', 'professional'] as $category)
                <option value="{{ $category }}" {{ $competition->category == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>

        <!-- Przycisk submit -->
        <button type="submit" class="button-27">Zaktualizuj</button>
    </form>
</div>
@endsection
