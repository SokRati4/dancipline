@extends('layouts.app') 
@section('custom_styles')
<style>
    label{
        font-size: 1.2em;
        
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
.attachment-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.delete-icon {
    font-size: 1.5em;
    color: #1f1f1f;
    cursor: pointer;
    margin-left: 10px;
}

.delete-icon:hover {
    color: #333;
}
    .form-group{
        margin-bottom: 20px;
    }
    .image-div{
        margin-bottom: 20px;
    }
    .attachment-image {
        border-radius: 10px; /* Okrągłe granice */
        object-fit: cover; 
        max-width: 150px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <h1>Edytuj notatkę</h1>


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notes.update', $note->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="session_id" value="{{ $session_id }}">
        <input type="hidden" name="content" id="content">

        <div class="form-group">
            <label for="editor">Treść notatki</label>
            <div id="editor" style="height: 300px;">{!! old('content', $note->content) !!}</div>
        </div>

        <div>
            @if($note->attachments->count() > 0)
                @foreach($note->attachments as $attachment)
                    <h3>Załączniki:</h3>
                    <div class="attachment-item" style="display: flex; align-items: center;">
                        <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Attachment" class="attachment-image" >
                        <i class="fas fa-trash-alt delete-icon" onclick="deleteAttachment({{ $attachment->id }})"></i>
                    </div>
                @endforeach
            @else
                <div class="image-div">
                    <input type="file" name="image" id="image" accept="image/*" style="display:none;">
                    <label for="image" class="button-27-small">Wybierz plik</label>
                    <span id="file-name">Nie wybrano pliku</span>
                </div>
            @endif
        </div>

        <button type="submit" class="button-27">Zapisz notatkę</button>
    </form>

    <form id="delete-attachment-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<script>
    // Inicjalizacja Quill
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                ['link', 'image', 'video'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    document.querySelector('form[action="{{ route('notes.update', $note->id) }}"]').onsubmit = function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    };
    document.addEventListener('DOMContentLoaded', function() {
    // Sprawdź, czy załącznik już istnieje
    if (!document.querySelector('.attachment-item')) {
        document.getElementById('image').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Nie wybrano pliku';
            document.getElementById('file-name').textContent = fileName;
        });
    }
});

    function deleteAttachment(attachmentId) {
        if (confirm('Czy na pewno chcesz usunąć ten załącznik?')) {
            fetch(`/attachments/${attachmentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Wystąpił problem podczas usuwania załącznika.');
                }
            })
            .catch(error => console.error('Błąd:', error));
        }
    }
</script>
@endsection
@endsection