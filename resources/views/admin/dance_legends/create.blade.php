@extends('layouts.admin')

@section('content')
    <h1>Create Dance Legend</h1>
    <form action="{{ route('admin.dance_legends.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="partner1_name">Partner 1 Name</label>
            <input type="text" name="partner1_name" id="partner1_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="partner2_name">Partner 2 Name</label>
            <input type="text" name="partner2_name" id="partner2_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="best_results">Best Results</label>
            <textarea name="best_results" id="best_results" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="known_for">Known For</label>
            <textarea name="known_for" id="known_for" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="videos">Videos</label>
            <textarea name="videos" id="videos" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Legend</button>
    </form>
@endsection
