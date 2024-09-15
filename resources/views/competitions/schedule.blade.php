@extends('layouts.app')
@section('custom_styles')
<style>
    .next-competition-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .next-competition {
        background-color: #ffffff;
        border: 2px solid #1f1f1f;
        border-radius: 15px;
        width: 60%;
        padding: 20px;
    }

    .competition-details {
        text-align: left;
        color: #333;
    }

    .competition-title {
        text-align: center;
        font-size: 1.8em;
        margin-bottom: 15px;
        color: #1f1f1f;
    }

    h3#next-comp {
        text-align: center;
        margin: 10px 0;
        font-size: 1.6em;
        color: #1f1f1f;
    }
</style>
@endsection
@section('content')
    <h1>Harmonogram zawodów</h1>
    <div id="calendar"></div>
    <h3 id="next-comp">Następny zawod</h3>
    @if($nextCompetition)
        <div class="next-competition-container">
            <div class="next-competition">
                <div class="competition-details">
                    <h3 class="competition-title">{{ $nextCompetition->name }}</h3>
                    <p><strong>Data:</strong> {{ $nextCompetition->start_date }}</p>
                    <p><strong>Miejsce:</strong> {{ $nextCompetition->location }}</p>
                    <p><strong>Kategoria:</strong> {{ $nextCompetition->category }}</p>
                    <p><strong>Ile zostało dni:</strong> {{ floor(now()->diffInDays($nextCompetition->start_date)) }} dni</p>
                </div>
            </div>
        </div>
    @else
        <p>Nie masz żadnych nadchodzących turniejów:(</p>
    @endif
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            let competitions = @json($competitions);

            $('#calendar').fullCalendar({
                defaultView: 'month',
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    let startDate = moment(start).format('YYYY-MM-DD');
                    let endDate = moment(end).format('YYYY-MM-DD');
                    

                    window.location.href = "{{ url('competitions/create_with_date') }}/" + startDate ;
                    
                },
                events: [
                    {
                    title: 'Competitions Period',
                    start: '{{ $startDate }}',
                    end: moment('{{ $endDate }}').add(1, 'days').format('YYYY-MM-DD'), 
                    rendering: 'background',
                    color: '#1f1f1f'
                    },
                    ...competitions.map(comp => ({
                        title: moment(comp.start_date).format('YYYY-MM-DD') + ' ' + comp.name,
                        start: comp.start_date,
                        end: moment(comp.end_date).add(1, 'days').format('YYYY-MM-DD'),
                        id: comp.id,
                        type: comp.type,
                        color: '#333'
                    })),
                    
                ],
                eventRender: function(event, element) {
                element.find('.fc-content').append(`
                    <div class="event-actions">
                        <i class="fa fa-edit" onclick="editSession(${event.id})"></i>
                        <i class="fa fa-trash" onclick="deleteSession(${event.id})"></i>
                    </div>
                `);
            },
                validRange: {
                    start: '{{ $startDate }}',
                    end: moment('{{ $endDate }}').format('YYYY-MM-DD')
                }
            });
        
            window.editSession = function(id) { 
                window.location.href =  "{{ url('competitions/edit') }}/" + id ;
            };

            window.deleteSession = function(id) {
                if (confirm('Czy na pewno chcesz usunąć ten zawod?')) {
                    $.ajax({
                        url: '/competitions/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(result) {
                        if (result.success) {
                            $('#calendar').fullCalendar('removeEvents', id);
                        } else {
                            alert('Błąd podczas usuwania zawodu');
                        }
                    },
                    error: function(err) {
                        alert('Błąd podczas usuwania zawodu');
                    }
                    });
                }
            }
        });
    </script>
@endsection