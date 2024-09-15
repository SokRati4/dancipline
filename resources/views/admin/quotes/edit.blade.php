@extends('layouts.admin')

@section('content')
    <h1>Edit Quote</h1>
    <form action="{{ route('admin.quotes.update', $quote->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="quote">Quote's text</label>
            <input type="text" name="quote" id="quote" class="form-control" value="{{ $quote->quote }}" required>
        </div>
        <div class="form-group">
            <label for="author">Author of the quote</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ $quote->author }}" required>
        </div>
        <button type="submit" class="button-27-icon">Update quote</button>
    </form>
@endsection
