@extends('layouts.admin')

@section('content')
    <h1>Dance Legends</h1>
    <form action="{{ route('admin.dance_legends.create') }}" method="GET">
        <button class="button-27-icon">
            <i class="fas fa-add"></i> <!-- Ikona edycji -->
        </button>
    </form>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Partner 1 Name</th>
                <th>Partner 2 Name</th>
                <th>Best Results</th>
                <th>Known For</th>
                <th>Bio</th>
                <th>Videos</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($legends as $legend)
                <tr>
                    <td>{{ $legend->id }}</td>
                    <td>{{ $legend->partner1_name }}</td>
                    <td>{{ $legend->partner2_name }}</td>
                    <td>{{ $legend->best_results }}</td>
                    <td>{{ $legend->known_for }}</td>
                    <td>{{ $legend->bio }}</td>
                    <td>{{ $legend->videos }}</td>
                    <td>
                        <form action="{{ route('admin.dance_legends.edit', $legend->id) }}" method="GET"  style="display:inline;">
                            <button class="button-27-icon">
                                <i class="fas fa-edit"></i> <!-- Ikona edycji -->
                            </button>
                        </form>
                        <form action="{{ route('admin.dance_legends.destroy', $legend->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-27-icon" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i> <!-- Ikona usunięcia -->
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
