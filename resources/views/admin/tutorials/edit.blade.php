@extends('layouts.admin')

@section('content')
    <h1>Edit Tutorial</h1>
    <form action="{{ route('admin.tutorials.update', $tutorial->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $tutorial->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $tutorial->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" name="url" id="url" class="form-control" value="{{ $tutorial->url }}" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (HH:MM:SS)</label>
            <input type="text" name="duration" id="duration" class="form-control" value="{{ $tutorial->duration }}" required>
        </div>
        <div class="form-group">
            <label for="level">Level</label>
            <select name="level" id="level" class="form-control" required>
                <option value="beginner" @if($tutorial->level == 'beginner') selected @endif>Beginner</option>
                <option value="amateur" @if($tutorial->level == 'amateur') selected @endif>Amateur</option>
                <option value="professional" @if($tutorial->level == 'professional') selected @endif>Professional</option>
            </select>
        </div>
        <button type="submit" class="button-27-icon">Update tutorial</button>
    </form>
@endsection
