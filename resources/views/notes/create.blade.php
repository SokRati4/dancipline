@extends('layouts.app')
@section('custom_styles')
    <style>
        label{
        font-size: 1.2em;
        
    }
    .form-group{
        margin-bottom: 20px;
    }
        .ql-toolbar{
        margin-top: 10px;
    }
        .button-27-small {
  appearance: none;
  background-color: #1f1f1f;
  border: 2px solid #1A1A1A;
  border-radius: 15px;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: Roobert,-apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
  font-size: 16px;
  font-weight: 600;
  line-height: normal;
  min-height: 60px;
  max-width: 300px;
  outline: none;
  padding: 16px 24px;
  text-align: center;
  text-decoration: none;
  transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  width: 100%;
  will-change: transform;
}

.button-27-small:disabled {
  pointer-events: none;
}

.button-27-small:hover {
  box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
  transform: translateY(-2px);
}

.button-27-small:active {
  box-shadow: none;
  transform: translateY(0);
}
.image-div{
        margin-bottom: 20px;
    }
    </style>
@endsection
@section('content')
<div class="container">
    <h1>Utwórz nową notatkę</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="session_id" value="{{ $session_id }}">
        <input type="hidden" name="content" id="content">

        <div class="form-group">
            <label for="editor">Treść notatki</label>
            <div id="editor" style="height: 300px;">{{ old('content') }}</div>
        </div>

        <div class="image-div">
            <input type="file" name="image" id="image" accept="image/*" style="display:none;">
            <label for="image" class="button-27-small">Wybierz plik</label>
            <span id="file-name">Nie wybrano pliku</span>
        </div>



        <button type="submit" class="button-27">Zapisz notatkę</button>
    </form>
</div>
<form id="delete-attachment-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<script>
    const quill = new Quill('#editor', {
        theme: 'snow'
    });


    document.querySelector('form[action="{{ route('notes.store') }}"]').onsubmit = function() {
    document.querySelector('#content').value = quill.root.innerHTML;
    };




    function saveToServer(file, sessionId, callback) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('session_id', sessionId); 

        fetch('/upload-image', { 
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.url) {
                callback(result.url); 
            } else {
                console.error('Błąd przy zapisywaniu obrazu:', result);
            }
        })
        .catch(error => {
            console.error('Błąd:', error);
        });
    }
</script>
@endsection