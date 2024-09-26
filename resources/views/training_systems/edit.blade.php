@extends("layouts.app")
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
}

.form-container label {
    display: block;
    color: #ffffff;
    font-size: 14px;
    margin-bottom: 6px;
}

.form-container input[type="text"],
.form-container input[type="date"],
.form-container input[type="number"],
.form-container textarea,
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
.form-container input[type="number"]:focus,
.form-container textarea:focus,
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
    <h1>Edytuj system treningowy</h1>
    <form id="trainingSystemForm" action="{{ route('training_systems.update', $trainingSystem) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Nazwa:</label>
        <input type="text" id="name" name="name" value="{{ $trainingSystem->name }}" required>
        <label for="description">Opis:</label>
        <textarea id="description" name="description">{{ $trainingSystem->description }}</textarea>
        <label for="start_date">Data razpoczęcia:</label>
        <input type="date" id="start_date" name="start_date" value="{{ $trainingSystem->start_date }}" required>
        <label for="end_date">Data zakończenia:</label>
        <input type="date" id="end_date" name="end_date" value="{{ $trainingSystem->end_date }}">
        <label for="system_type">Typ systemu:</label>
        <select id="system_type" name="system_type" required>
            <option value="vacation" {{ $trainingSystem->system_type == 'vacation' ? 'selected' : '' }}>Wakacje</option>
            <option value="pre-tournament" {{ $trainingSystem->system_type == 'pre-tournament' ? 'selected' : '' }}>Przygotowanie do turnieju</option>
            <option value="dance_camp" {{ $trainingSystem->system_type == 'dance_camp' ? 'selected' : '' }}>Obóz taneczny</option>
            <option value="off-season" {{ $trainingSystem->system_type == 'off-season' ? 'selected' : '' }}>Przerwa pomiędzysezonowa</option>
        </select>
        <label for="dance_style">Styl taneczny:</label>
        <select id="dance_style" name="dance_style" required>
            <option value="ST" {{ $trainingSystem->dance_style == 'ST' ? 'selected' : '' }}>ST</option>
            <option value="LA" {{ $trainingSystem->dance_style == 'LA' ? 'selected' : '' }}>LA</option>
            <option value="10 dances" {{ $trainingSystem->dance_style == '10 dances' ? 'selected' : '' }}>10 tańcow</option>
            <option value="Smooth" {{ $trainingSystem->dance_style == 'Smooth' ? 'selected' : '' }}>Smooth</option>
        </select>
        <button class="button-27" type="submit">Zaktualizuj</button>
    </form>
</div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('trainingSystemForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    startDateInput.addEventListener('change', function() {
        const startDate = moment(startDateInput.value);
        const oneYearLater = startDate.clone().add(1, 'years').format('YYYY-MM-DD');
        endDateInput.setAttribute('min', startDateInput.value);
        endDateInput.setAttribute('max', oneYearLater);
        if (moment(endDateInput.value).isBefore(startDate)) {
            endDateInput.value = startDateInput.value;
        }
    });

    form.addEventListener('submit', function(event) {
        const startDate = moment(startDateInput.value);
        const endDate = moment(endDateInput.value);
        const oneYearLater = startDate.clone().add(1, 'years');

        if (endDate.isBefore(startDate)) {
            alert('Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.');
            event.preventDefault();
        } else if (endDate.isAfter(oneYearLater)) {
            alert('Data zakończenia nie może być dłuższa niż rok od daty rozpoczęcia.');
            event.preventDefault();
        }
    });
});
    </script>
@endsection
