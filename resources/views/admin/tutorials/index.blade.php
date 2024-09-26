@extends('layouts.admin')

@section('content')
    <h1>Tutorials</h1>
    <form action="{{ route('admin.tutorials.create') }}" method="GET">
        <button class="button-27-icon">
            <i class="fas fa-add"></i> 
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
                <th>Title</th>
                <th>Description</th>
                <th>URL</th>
                <th>Duration</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tutorials as $tutorial)
                <tr>
                    <td>{{ $tutorial->id }}</td>
                    <td>{{ $tutorial->title }}</td>
                    <td>{{ $tutorial->description }}</td>
                    <td>{{ $tutorial->url }}</td>
                    <td>{{ $tutorial->duration }}</td>
                    <td>{{ ucfirst($tutorial->level) }}</td>
                    <td>
                        <form action="{{ route('admin.tutorials.edit', $tutorial->id) }}" method="GET"  style="display:inline;">
                            <button class="button-27-icon">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.tutorials.destroy', $tutorial->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-27-icon" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
