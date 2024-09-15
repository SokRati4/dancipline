@extends('layouts.admin')
@section('custom_styles')
<style>
table th, table td {
    padding: 15px; /* Zwiększa przestrzeń w komórkach */
    font-size: 18px; /* Zwiększa rozmiar czcionki */
    border-bottom: 2px solid #ccc; /* Zwiększa grubość linii pod wierszem */
}

th {
    font-weight: bold;
    text-transform: uppercase;
}
tr {
    border-bottom: 2px solid #ccc; /* Zwiększa grubość linii */
}

</style>
@endsection
@section('content')
    <h1>Users List</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    @if( $user->role != 'admin')
                    <td>
                        <form action="{{ route('admin.users.edit', $user->id) }}" method="GET"  style="display:inline;">
                            <button class="button-27-icon">
                                <i class="fas fa-edit"></i> <!-- Ikona edycji -->
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-27-icon" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash-alt"></i> <!-- Ikona usunięcia -->
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
