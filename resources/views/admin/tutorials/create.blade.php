@extends('layouts.admin')

@section('content')
    <h1>Create Tutorial</h1>
    <form action="{{ route('admin.tutorials.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" name="url" id="url" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="level">Level</label>
            <select name="level" id="level" class="form-control" required>
                <option value="beginner">Beginner</option>
                <option value="amateur">Amateur</option>
                <option value="professional">Professional</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Tutorial</button>
    </form>
@endsection
