@extends('layouts.app')
@section('custom_styles')
<style>
    .note-content p{
        font-size: 1.2em;
    }
    .note-image {
        text-align: center; /* Centruje obrazek */
        margin-bottom: 20px;
    }

    .image-rounded {
        max-width: 200px;
        border-radius: 15px; /* Zaokrąglenie rogów */
        border: 3px solid #1f1f1f; /* Obwódka w kolorze #1f1f1f */
        cursor: zoom-in; /* Styl kursora */
    }
    * {
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  -ms-box-sizing:border-box;
  -o-box-sizing:border-box;
  box-sizing:border-box;
    }

    body {
        background: #f1f1f1;
        font-family:helvetica neue, helvetica, arial, sans-serif;
        font-weight:200;
    }

    #notebook-paper {
        width:960px;
        height:1109px;
        background: linear-gradient(to bottom,white 29px,#00b0d7 1px);
        margin:50px auto;
        background-size: 100% 30px;
        position:relative;
        padding-left:160px;
        padding-right:20px;
        overflow:hidden;
        border-radius:5px;
        -webkit-box-shadow:3px 3px 3px rgba(0,0,0,.2),0px 0px 6px rgba(0,0,0,.2);
        -moz-box-shadow:3px 3px 3px rgba(0,0,0,.2),0px 0px 6px rgba(0,0,0,.2);
        -ms-box-shadow:3px 3px 3px rgba(0,0,0,.2),0px 0px 6px rgba(0,0,0,.2);
        -o-box-shadow:3px 3px 3px rgba(0,0,0,.2),0px 0px 6px rgba(0,0,0,.2);
        box-shadow:3px 3px 3px rgba(0,0,0,.2),0px 0px 6px rgba(0,0,0,.2);
        &:before {
            content:'';
            display:block;
            position:absolute;
            z-index:1;
            top:0;
            left:140px;
            height:100%;
            width:1px;
            background:#db4034;
        }
    #content {
        margin-top:67px;
        font-size:20px;
        line-height:30px;
        p {
        margin:0 0 30px 0;
        }
    }
    }
</style>
@endsection
@section('content')
<div class="container">
    <div id="notebook-paper">
            <h1>{{$session->type}} trening {{ \Carbon\Carbon::parse($session->start_datetime)->translatedFormat('j F') }}</h1>
        <div id="content">
          <div class="hipsum">{!! $note->content !!}</div>
        </div>
      </div>

    @if($attachment)
      <div class="note-image mt-5 text-center">
          <h3>Załączone zdjęcie:</h3>
          <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
              <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Załącznik" class="image-rounded">
          </a>
      </div>
    @endif

    <a href="{{ route('notes.edit', $session->note_id) }}"><button class="button-27" >Edytuj notatkę</button></a>
</div>
@endsection