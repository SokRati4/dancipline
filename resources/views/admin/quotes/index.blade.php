@extends('layouts.admin')

@section('content')
    <h1>Quotes</h1>
    <form action="{{ route('admin.quotes.create') }}" method="GET">
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
                <th>Quote's text</th>
                <th>Author of the quote</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotes as $quote)
                <tr>
                    <td>{{ $quote->id }}</td>
                    <td>{{ $quote->quote }}</td>
                    <td>{{ $quote->author }}</td>
                    <td>
                        <form action="{{ route('admin.quotes.edit', $quote->id) }}" method="GET"  style="display:inline;">
                            <button class="button-27-icon">
                                <i class="fas fa-edit"></i> <!-- Ikona edycji -->
                            </button>
                        </form>
                        <form action="{{ route('admin.quotes.destroy', $quote->id) }}" method="POST" style="display:inline;">
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
