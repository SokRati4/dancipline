@extends("layouts.app")
@section('content')
    <h1>Harmonogram treningów dla {{ $trainingSystem->name }}</h1>
    <div id="calendar"></div>
    <form action="{{ route('training_systems.index') }}" method="GET">
        <button class="button-27" type="submit">Powrót do obecnego systemu treningowego</button>
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let existingSessions = @json($trainingSessions);

            $('#calendar').fullCalendar({
                defaultView: 'month',
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    let startDate = moment(start).format('YYYY-MM-DD');
                    let endDate = moment(end).format('YYYY-MM-DD');
                    

                    window.location.href = "{{ url('training_sessions/create_with_date') }}/" + startDate + "/{{ $trainingSystem->id }}";
                    
                },
                events: [
                    {
                    title: 'Training System Period',
                    start: '{{ $startDate }}',
                    end: moment('{{ $endDate }}').add(1, 'days').format('YYYY-MM-DD'), 
                    rendering: 'background',
                    color: '#1f1f1f'
                    },
                    ...existingSessions.map(session => ({
                        title:  moment(session.start_datetime).format('H:mm') + ' ' + session.type,
                        start: session.start_datetime,
                        end: session.end_datetime,
                        id: session.id,
                        type: session.type,
                        color: '#333'
                    })),
                    
                ],
                eventRender: function(event, element) {
                element.find('.fc-content').append(`
                    <div class="event-actions">
                        <i class="fa fa-edit" onclick="editSession(${event.id}, {{ $trainingSystem->id }})"></i>
                        <i class="fa fa-trash" onclick="deleteSession(${event.id})"></i>
                    </div>
                `);
            },
                validRange: {
                    start: '{{ $startDate }}',
                    end: moment('{{ $endDate }}').add(1, 'days').format('YYYY-MM-DD')
                }
            });
        
            window.editSession = function(id, system_id) { 
                window.location.href =  "{{ url('training_sessions/edit') }}/" + id + "/" + system_id;
            };

            window.deleteSession = function(id) {
                if (confirm('Czy na pewno chcesz usunąć tą sesję?')) {
                    $.ajax({
                        url: '/training_sessions/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(result) {
                        if (result.success) {
                            $('#calendar').fullCalendar('removeEvents', id);
                        } else {
                            alert('Błąd podczas usuwania sesji');
                        }
                    },
                    error: function(err) {
                        alert('Błąd podczas usuwania sesji');
                    }
                    });
                }
            }
        });
    </script>
@endsection
