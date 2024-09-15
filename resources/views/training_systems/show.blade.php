<!DOCTYPE html>
<html>
<head>
    <title>Show Training System</title>
</head>
<body>
    <h1>Training System Details</h1>
    <p>Name: {{ $trainingSystem->name }}</p>
    <p>Description: {{ $trainingSystem->description }}</p>
    <p>Start Date: {{ $trainingSystem->start_date }}</p>
    <p>End Date: {{ $trainingSystem->end_date }}</p>
    <p>Type: {{ $trainingSystem->system_type }}</p>
    <p>Completion: {{ $trainingSystem->completion_percentage }}%</p>
    <p>Dance Style: {{ $trainingSystem->dance_style }}</p>
    <a href="{{ route('training_systems.edit', $trainingSystem) }}">Edit</a>
    <form action="{{ route('training_systems.destroy', $trainingSystem) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
</body>
</html>
