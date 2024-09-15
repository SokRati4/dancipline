@extends('layouts.admin')

@section('content')
    <h1>Create Quote</h1>
    <form action="{{ route('admin.quotes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="quote">Quote's text</label>
            <input type="text" name="quote" id="quote" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="author">Author of the quote</label>
            <input type="text" name="author" id="author" class="form-control" required>
        </div>
        <button type="submit" class="button-27-icon">Create Quote</button>
    </form>
@endsection
