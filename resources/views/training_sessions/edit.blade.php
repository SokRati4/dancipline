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
    .form-container input[type="datetime-local"],
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
    .form-container input[type="datetime-local"]:focus,
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

    .form-container input[type="checkbox"] {
        display: none;
    }

    .form-container input[type="checkbox"] + label {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        font-size: 14px;
        color: #ffffff;
        user-select: none;
        display: inline-block;
        margin-bottom: 15px;
        margin-right: 15px; 
    }

    .form-container input[type="checkbox"] + label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 2px solid #444;
        border-radius: 4px;
        background-color: #333;
    }

    .form-container input[type="checkbox"]:checked + label:before {
        background-color: #ffffff;
        border-color: #ffffff;
    }

    .form-container input[type="checkbox"]:checked + label:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 5px;
        width: 10px;
        height: 10px;
        background-color: #ffffff;
        border-radius: 2px;
    }

    #dances_planned_container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px; 
    }

    #dances_planned_container div {
        flex: 1 1 45%;
        display: flex;
        align-items: center;
    }

    .checkbox-label {
        margin-left: 5px;
    }

    .form-container .checkbox-container {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .form-container .checkbox-container label {
        margin-bottom: 0;
    }

    .checkbox-container {
        display: block; 
        margin-bottom: 15px;
        padding-left: 25px;
    }

    .form-container .checkbox-container:last-child {
        margin-bottom: 15px; 
    }
</style>
@endsection
@section('content')
<div class="form-container">
    <h1>Edytuj sesję treningową</h1>
    <form action="{{ route('training_sessions.update', $trainingSession) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="system_id" value="{{ $system_id }}">
        
        <label for="type">Rodzaj sesji:</label>
        <select id="type" name="type" required>
            <option value="individual" {{ $trainingSession->type == 'individual' ? 'selected' : '' }}>Lekcja</option>
            <option value="group" {{ $trainingSession->type == 'group' ? 'selected' : '' }}>Grupowe</option>
            <option value="stamina" {{ $trainingSession->type == 'stamina' ? 'selected' : '' }}>Stamina</option>
            <option value="self" {{ $trainingSession->type == 'self' ? 'selected' : '' }}>Trening własny</option>
        </select>

        <label for="start_datetime">Czas razpoczęcia:</label>
        <input type="datetime-local" id="start_datetime" name="start_datetime" value="{{ $trainingSession->start_datetime }}" required>
        
        <label for="end_datetime">Czas zakończenia:</label>
        <input type="datetime-local" id="end_datetime" name="end_datetime" value="{{ $trainingSession->end_datetime }}" required>
        
        
        
        <label for="style">Styl:</label>
        <select id="style" name="style" required>
            @if($systemStyle == '10 dances')
                <option value="ST" {{ $trainingSession->style == 'ST' ? 'selected' : '' }}>ST</option>
                <option value="LA" {{ $trainingSession->style == 'LA' ? 'selected' : '' }}>LA</option>
            @else
                <option value="{{ $systemStyle }}" {{ $trainingSession->style == $systemStyle ? 'selected' : '' }}>{{ $systemStyle }}</option>
            @endif
        </select>
        
        <label for="dances_planned">Zaplanowane tańce:</label>
        <div id="dances_planned_container">
        </div>
        
        <div class="checkbox-container">
            <input type="checkbox" id="five_dances" name="five_dances" value="1">
            <label for="five_dances">Czy będzie piątka?</label>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="with_partner" name="with_partner" value="1">
            <label for="with_partner">Sesja z partnerem?</label>
        </div>

        <button class="button-27" type="submit">Zaktualizuj</button>
    </form>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const style = '{{ $systemStyle }}';
            const dancesPlannedContainer = document.getElementById('dances_planned_container');
            const startDateTimeInput = document.getElementById('start_datetime');
            const endDateTimeInput = document.getElementById('end_datetime');
            const selectedDate = '{{ \Carbon\Carbon::parse($trainingSession->start_datetime)->format('Y-m-d') }}';

            const dancesOptions = {
                'ST': ['Waltz', 'Tango', 'Viennese Waltz', 'Foxtrot', 'Quickstep'],
                'LA': ['Cha-Cha', 'Samba', 'Rumba', 'Paso', 'Jive'],
                'Smooth': ['Waltz', 'Tango', 'Viennese Waltz', 'Foxtrot', 'Quickstep', 'Cha-Cha', 'Samba', 'Rumba', 'Paso', 'Jive'],
                '10 dances': ['Waltz', 'Tango', 'Viennese Waltz', 'Foxtrot', 'Quickstep', 'Cha-Cha', 'Samba', 'Rumba', 'Paso', 'Jive']
            };

            function updateDancesOptions(style) {
                dancesPlannedContainer.innerHTML = '';
                if (dancesOptions[style]) {
                    dancesOptions[style].forEach(function (dance) {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'dances_planned[]';
                        checkbox.value = dance;
                        checkbox.id = 'dance_' + dance;

                        const label = document.createElement('label');
                        label.htmlFor = 'dance_' + dance;
                        label.textContent = dance;

                        const div = document.createElement('div');
                        div.appendChild(checkbox);
                        div.appendChild(label);

                        dancesPlannedContainer.appendChild(div);
                    });
                }
            }

            updateDancesOptions(style);

            function setMinTimeForStart() {
                const now = moment.tz(moment(), moment.tz.guess());
                now.add(5, 'minutes'); 
                const isoString = now.format('YYYY-MM-DDTHH:mm');
                startDateTimeInput.min = isoString;
                startDateTimeInput.value = isoString;
            }

            function restrictDateInput(input) {
                const datetime = moment.tz(input.value, moment.tz.guess());
                const restrictedDatetime = moment.tz(selectedDate + 'T' + datetime.format('HH:mm'), moment.tz.guess());
                input.value = restrictedDatetime.format('YYYY-MM-DDTHH:mm');
            }

            function setMinTimeForEnd() {
                const startDatetime = moment.tz(startDateTimeInput.value, moment.tz.guess());
                endDateTimeInput.min = startDatetime.format('YYYY-MM-DDTHH:mm');
            }

            startDateTimeInput.addEventListener('input', function() {
                restrictDateInput(startDateTimeInput);
                setMinTimeForEnd();
            });

            endDateTimeInput.addEventListener('input', function() {
                restrictDateInput(endDateTimeInput);
                const startDateTime = moment.tz(startDateTimeInput.value, moment.tz.guess());
                const endDateTime = moment.tz(endDateTimeInput.value, moment.tz.guess());

                if (endDateTime.isBefore(startDateTime)) {
                    endDateTimeInput.value = startDateTime.format('YYYY-MM-DDTHH:mm');
                }
            });

            setMinTimeForStart();
            restrictDateInput(startDateTimeInput);
            restrictDateInput(endDateTimeInput);
            setMinTimeForEnd();
        });
    </script>
@endsection