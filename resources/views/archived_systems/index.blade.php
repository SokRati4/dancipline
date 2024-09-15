@extends('layouts.app')
@section('content')
<h1>Archiwalne statystyki tygodniowe</h1>
@if ($archivedSystems)
    @foreach ($archivedSystems as $ASystemStat)
        <h2>Nazwa</h2>
        <p>Z: {{ $ASystemStat->start_date }} po: {{ $ASystemStat->end_date }}</p>
        <div class="chart-container">
                <canvas id="completionChart" class="chart" ></canvas>
                <canvas id="hoursChart" class="chart" ></canvas>
                <canvas id="styleChart" class="chart" ></canvas>
                <canvas id="typeChart" class="chart" ></canvas>
                <canvas id="intensityChart" class="chart" ></canvas>
        </div>
    @endforeach
@else
    <h2>Brak zarchiwizowanych statystyk systemu</h2>
@endif
@endsection
@section('scripts')
<script>
    const stats = @json($archivedSystems);
    
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
                backgroundColor: ['#4CAF50', '#e0e0e0']
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
                backgroundColor: ['#2196F3', '#e0e0e0']
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
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
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
            labels: ['Stamina', 'Self', 'Leckje', 'Grupowe' ],
            datasets: [{
                data: [stats.training_type_stamina_percentage, stats.training_type_self_percentage, stats.training_type_individual_percentage, stats.training_type_group_percentage],
                backgroundColor: ['#8E44AD', '#2980B9', '#27AE60', '#FFC107' ]
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
                backgroundColor: ['#E74C3C','#e0e0e0']
            }]
        },
    });
</script>
@endsection