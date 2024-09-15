@extends('layouts.app')
@section('custom_styles')
<style>
.carousel-item {
            height: 250px; /* Adjust the height as needed */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border-radius: 15px; /* Rounded corners */
            margin: 0; /* Add margin */
            background-color: #1f1f1f; /* Light background color */
            padding: 15px; /* Add padding */
            color:#ffffff;
            font-weight: bold;
        }
        .slick-list{
            border-radius: 15px;
        }
        .carousel-item .session-info {
            margin-bottom: 15px;
        }
        .carousel-item .btn {
            display: none;
            margin: 5px; /* Add margin between buttons */
        }
        .chart-container{
            margin-bottom: 20px;
        }
        
</style>
@endsection
@section('content')
    @if ($currentSystem)
        <h1>{{ $currentSystem->name }}</h1>
        <form action="{{ route('training_systems.edit', $currentSystem) }}" method="GET">
            <button class="button-27" type="submit">Edytuj</button>
        </form>
        <form action="{{ route('training_systems.schedule', $currentSystem) }}" method="GET">
            <button class="button-27" type="submit">Zarządzaj sesjami</button>
        </form>
        <form action="{{ route('training_systems.destroy', $currentSystem) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="button-27" type="submit">Usuń</button>
        </form>
        <h4>Plan sesji treningowych</h4>
        <div class="training-sessions-carousel">
            @foreach ($trainingSessions as $session)
            @if ($session->completed == 0)
            <div class="carousel-item" data-session-id="{{ $session->id }}" data-started="{{ $session->started }}" data-completed="{{ $session->completed }}">
                    <div class="session-info">
                        <h5>{{ $session->type }} trening {{ \Carbon\Carbon::parse($session->start_datetime)->translatedFormat('j F') }} </h5>
                        <p data-start="{{ $session->start_datetime }}">Czas rozpoczęcia: {{ \Carbon\Carbon::parse($session->start_datetime)->format('H:i') }}</p>
                        <p data-end="{{ $session->end_datetime }}">Czas zakończenia: {{ \Carbon\Carbon::parse($session->end_datetime)->format('H:i') }}</p>
                        <p>Styl: {{ $session->style }}</p>
                        <p class="timer-container">Zostało czasu: <span class="timer" data-start="{{ $session->start_datetime }}" data-end="{{ $session->end_datetime }}"></span></p>
                    </div>
                    <button class="btn btn-success start-btn" @if($session->started == 1) style="display:none;" @endif>Start</button>
                    <button class="btn btn-danger finish-btn" @if($session->completed == 1) style="display:none;" @endif>Finish</button>
                    @if($session->started == 1 && $session->completed == 0)
                        <p class="status">Trening jest w trakcie</p>
                    @elseif($session->completed == 1)
                        <p class="status">Sesja zakończona</p>
                    @endif
                </div>
            @endif
            @endforeach
        </div>
        <h4>Statystyka systemu z {{ \Carbon\Carbon::parse($currentSystem->start_date)->translatedFormat('j F') }} po {{ \Carbon\Carbon::parse($currentSystem->end_date)->translatedFormat('j F') }}</h4>
        <div class="chart-container">
                <canvas id="completionChart" class="chart" ></canvas>
                <canvas id="hoursChart" class="chart" ></canvas>
                <canvas id="styleChart" class="chart" ></canvas>
                <canvas id="typeChart" class="chart" ></canvas>
                <canvas id="intensityChart" class="chart" ></canvas>
        </div>
        @if ($weeklyStats['total_training_minutes'] != 0)
        <div class="period">
            <h4>Statystyka tygodniowa z {{ \Carbon\Carbon::parse($weekStart)->translatedFormat('j F') }} po {{ \Carbon\Carbon::parse($weekEnd)->translatedFormat('j F') }}</h4>
        </div>
        <div class="weekly">
            <div id="totalMinutes"  >
                <h1>{{ $weeklyStats['total_training_minutes'] }}</h1>
                <p class="subtitle">minut zatańczono</p>
            </div>
            <div id="sessionsPercentage">
                <h1>{{ round($weeklyStats['completed_sessions_percentage']) }}%</h1>
                <p class="subtitle">sesji zakończono</p>
            </div>
            <div id="finalsDances">
                <h1>{{ $weeklyStats['finals_danced'] }}</h1>
                <p class="subtitle">piątek zatańczono</p>
            </div>
            <div id="daysTrained">
                <h1>{{ $weeklyStats['days_trained'] }}/7</h1>
                <p class="subtitle">dni treningowe w tym wygodniu</p>
            </div>
        </div>
        @endif
        <div class="offcanvas offcanvas-start text-bg-dark" id="demo">
            <div class="offcanvas-header">
              <h1 class="offcanvas-title">Archiwum</h1>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <p><a href="{{ route('archived.systems') }}">Zarchiwizowane statystyki systemu</a></p>
                <p><a href="{{ route('archived.weekly_stats') }}">Archiwalne statystyki tygodniowe</a></p>
            </div>
          </div>
          <div class="container-fluid mt-3">
            <h3>Archiwum</h3>
            <button class="button-27" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
              Otwórz menu archiwum
            </button>
          </div>
    @else
        <p>Nie ma aktualnych systemów treningowych</p>
        <a href="{{ route('training_systems.create') }}"><button class="button-27" >Utwórz nowy system treningowy</button></a>
    @endif
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.training-sessions-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
            });

            function updateTimers() {
    $('.timer').each(function() {
        const startTime = moment($(this).data('start'));
        const endTime = moment($(this).data('end'));
        const now = moment();
        const sessionElement = $(this).closest('.carousel-item');
        const started = sessionElement.data('started');
        const completed = sessionElement.data('completed');

        if (completed == 1) {
            $(this).closest('.timer-container').hide();
            return;
        }

        if (started == 1) {
            if (now.isBefore(endTime)) {
                $(this).text(endTime.diff(now, 'minutes') + ' minutes');
            } else {
                if (now.isAfter(endTime.clone().add(15, 'minutes'))) {
                    $(this).closest('.timer-container').hide();
                    sessionElement.find('.status').text('Sesja zakończona');
                } else {
                    $(this).text('Sesja zakończona');
                }
            }
        } else {
            if (now.isBefore(startTime)) {
                $(this).text(startTime.diff(now, 'hours') + ' godzin');
            } else {
                $(this).text('Sesja rozpoczęta');
            }
        }
    });
}


function showButtons() {
    $('.carousel-item').each(function() {
        const startTime = moment($(this).find('p[data-start]').data('start'));
        const endTime = moment($(this).find('p[data-end]').data('end'));
        const now = moment();
        const started = $(this).data('started');
        const completed = $(this).data('completed');

        if (completed == 1) {
            $('.training-sessions-carousel').slick('slickRemove', $(this).index());
            return;
        }

        if (started == 1) {
            $(this).find('.start-btn').hide();
            if (now.isAfter(endTime.clone().add(15, 'minutes'))) {
                $('.training-sessions-carousel').slick('slickRemove', $(this).index());
            } else {
                $(this).find('.finish-btn').hide();
                $(this).find('.status').text('Trening jest w trakcie');
            }
        }

        if (completed == 1) {
            $(this).find('.start-btn').hide();
            $(this).find('.finish-btn').hide();
            $(this).find('.status').text('Sesja zakończona');
            $(this).find('.timer-container').hide(); // Ukryj timer po zakończeniu sesji
        }

        if (now.isBetween(startTime.clone().subtract(15, 'minutes'), startTime.clone().add(30, 'minutes'))) {
            if (!started) {
                $(this).find('.start-btn').show();
            }
        }
        if (now.isBetween(endTime.clone().subtract(15, 'minutes'), endTime.clone().add(30, 'minutes'))) {
            if (!completed) {
                $(this).find('.finish-btn').show();
            }
        }
    });
}

        function handleStartButtonClick(sessionId) {
    const now = moment().format('YYYY-MM-DD HH:mm:ss');
    $.ajax({
        url: '/training_sessions/' + sessionId + '/start',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            start_confirmed_at: now
        },
        success: function(response) {
    alert('Session started successfully');
    const sessionElement = $('.carousel-item[data-session-id="' + sessionId + '"]');
    sessionElement.find('.start-btn').hide();
    sessionElement.data('started', 1);
    sessionElement.find('.status').remove();
    sessionElement.append('<p class="status">Trening jest w trakcie</p>');
},
        error: function(error) {
            alert('Nie udało się rozpocząć sesji');
        }
    });
}

function handleFinishButtonClick(sessionId) {
    const now = moment().format('YYYY-MM-DD HH:mm:ss');
    $.ajax({
        url: '/training_sessions/' + sessionId + '/finish',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            end_confirmed_at: now
        },
        success: function(response) {
            alert('Sesja zakończona pomyślnie');
            const sessionElement = $('.carousel-item[data-session-id="' + sessionId + '"]');
            $('.training-sessions-carousel').slick('slickRemove', sessionElement.index());
        },
        error: function(error) {
            alert('Nie udało się zakończyć sesji');
        }
    });
}



$('.start-btn').click(function() {
    const sessionId = $(this).closest('.carousel-item').data('session-id');
    handleStartButtonClick(sessionId);
});

$('.finish-btn').click(function() {
    const sessionId = $(this).closest('.carousel-item').data('session-id');
    handleFinishButtonClick(sessionId);
});


            setInterval(function() {
                updateTimers();
                showButtons();
            }, 60000); // Update every minute

            updateTimers();
            showButtons();

        });

        document.addEventListener('DOMContentLoaded', function() {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        // Prześlij strefę czasową do serwera
        fetch('/set-timezone', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ timezone: timezone })
        });
    });
    
    </script>
    <script>
        const stats = @json($stats);
        
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    font: {
                        size: 16
                    }
                },
                legend: {
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    bodyFont: {
                        size: 14
                    },
                    titleFont: {
                        size: 16
                    }
                }
            }
        };
        // Completion Chart
        const completionCtx = document.getElementById('completionChart').getContext('2d');
        new Chart(completionCtx, {
            type: 'doughnut',
            data: {
                labels: ['% zakończonych sesji'],
                datasets: [{
                    data: [stats.completed_sessions_percentage, 100 - stats.completed_sessions_percentage],
                    backgroundColor: ['#0a0a0a', '#e0e0e0']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Procent ukończonych sesji'
                }
            }
        });

        // Hours Chart
        const completedHoursPercentage = Number(stats.completed_training_hours)/10;
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'doughnut',
            data: {
                labels: ['Ukończone godziny'],
                datasets: [{
                    data: [completedHoursPercentage, 10 - completedHoursPercentage],
                    backgroundColor: ['#4b4b4b', '#e0e0e0']
                }]
            },
            
        });

        // Style Chart
        const styleCtx = document.getElementById('styleChart').getContext('2d');
        new Chart(styleCtx, {
            type: 'pie',
            data: {
                labels: ['LA', 'ST', 'Smooth'],
                datasets: [{
                    data: [stats.style_la_percentage, stats.style_st_percentage, stats.style_smooth_percentage],
                    backgroundColor: ['#003300', '#3c2f2f', '#6f4f28']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Procent stylu sesji'
                }
            }
        });

        // Type Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: ['Stamina', 'Self', 'Lekcje', 'Grupowe' ],
                datasets: [{
                    data: [stats.training_type_stamina_percentage, stats.training_type_self_percentage, stats.training_type_individual_percentage, stats.training_type_group_percentage],
                    backgroundColor: ['#2a2a6b', '#004d40', '#4f4f4f', '#3b3b2b' ]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Procent typu sesji'
                }
            }
        });

        // Intensity Chart
        const intensityCtx = document.getElementById('intensityChart').getContext('2d');
        new Chart(intensityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Średnia intensywność'],
                datasets: [{
                    data: [stats.average_intensity, 10-stats.average_intensity ],
                    backgroundColor: ['#2d0044','#e0e0e0']
                }]
            },
        });
    </script>
@endsection