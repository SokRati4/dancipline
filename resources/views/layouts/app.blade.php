<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script> 
    <title>@yield('title', 'Dancipline')</title>
    @yield('custom_styles')
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            background-color: #1f1f1f;
            color: #ffffff;
            padding: 15px 0;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header nav .nav-links {
            display: flex;
            gap: 20px;
        }

        header nav .nav-links a {
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            color: #ffffff;
            text-decoration: none;
        }

        header nav .nav-links a:hover {
            background-color: #333;
            color: #ffffff;
        }

        .offcanvas.text-bg-dark {
            background-color: #1f1f1f !important;
        }

        .offcanvas .offcanvas-header, 
        .offcanvas .offcanvas-body {
            background-color: #1f1f1f; 
        }
        .offcanvas .offcanvas-body a {
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            color: #ffffff;
            text-decoration: none;

        }
        .offcanvas .offcanvas-body a:hover {
            background-color: #333;
            color: #ffffff;

        }

        /* Main Container */
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .main-container .content {
            max-width: 1200px;
            width: 100%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #calendar {
                max-width: 900px;
                margin: 0 auto;
            }

        /* Footer */
        footer {
    background-color: #1f1f1f;
    flex-shrink: 0;
    color: #ffffff;
    text-align: center;
    padding: 15px 0;
    margin-top: auto;
    position: relative;
    width: 100%;
}

footer .quote-container {
    margin: 20px 0;
    padding: 10px;
    background-color: #333;
    border-radius: 5px;
    color: #f7f7f7;
    font-style: italic;
}

footer blockquote {
    margin: 0;
    padding: 0;
    font-size: 1.2em;
}

footer blockquote p {
    margin: 0;
}

footer blockquote footer {
    margin-top: 10px;
    font-size: 0.9em;
    font-weight: bold;
}

        /* Responsive Design */
        @media (max-width: 768px) {
            header nav {
                flex-direction: column;
            }

            header nav .nav-links {
                flex-direction: column;
                gap: 10px;
            }
        }
        .chart-container {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-around;
            border: 2px solid #1f1f1f;
            padding: 20px;
        }
        .chart-container canvas {
            width: 200px !important;
            height: 200px !important;
            margin: 10px;
        }

/* CSS */
.button-27 {
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
  margin: 0;
  min-height: 60px;
  min-width: 0;
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
  margin-bottom: 5px;
}

.button-27:disabled {
  pointer-events: none;
}

.button-27:hover {
  box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
  transform: translateY(-2px);
}

.button-27:active {
  box-shadow: none;
  transform: translateY(0);
}
h1,h2,h3,h4 {
    margin-top: 20px !important;
    margin-bottom: 20px !important;
    text-align: center;

}
.fc-day-grid-event .fc-content {
    white-space: normal !important; /* Umożliwia zawijanie tekstu w komórkach */
}
/* Styl dla wydarzeń */
.fc-event, .fc-event-dot {
    background-color: #333; /* Ciemnoszary dla tła wydarzeń */
    border-color: #333; /* Ciemnoszary dla obramowania */
    color: #fff; /* Biały dla tekstu w wydarzeniach */
    height: auto !important; /* Pozwala na automatyczne dopasowanie wysokości do zawartości */
    line-height: 1.5; /* Opcjonalnie zwiększa odstępy między liniami */
}


/* Styl dla tła kalendarza */
.fc-bg {
    background-color: #1f1f1f; /* Czarny dla tła kalendarza */
}

.fc-day-number{
    color: #fff;
}
/* Styl dla nagłówków dni */
.fc-day-header {
    background-color: #1f1f1f; /* Czarny dla nagłówków dni */
    color: #fff; /* Biały dla tekstu w nagłówkach */
}

/* Styl dla ikon akcji */
.event-actions .fa-edit {
    color: #fff; /* Biały dla ikony edycji */
}

.event-actions .fa-trash {
    color: #fff; /* Biały dla ikony usuwania */
}
.weekly{
            border: 2px solid #333;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 100px; 
            padding: 100px;
        }
        .weekly h1 {
            font-size: 64px; /* Zmień na preferowany rozmiar */
        }
        .subtitle {
            font-size: 17px; /* Rozmiar czcionki dla podpisu */
            color: #888; /* Szary kolor tekstu */
            font-style: italic; /* Pochylenie tekstu */
            text-align: center; /* Wyśrodkowanie tekstu pod wartością */
            margin-top: 10px; /* Odstęp od głównej wartości */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Dancipline</div>
            <div class="nav-links">
                <a href="{{ route('training_systems.index') }}">Trening</a>
                <a href="{{ route('education.index') }}">Zasoby edukacyjne</a>
                <a href="{{ route('notes.index') }}">Notatki</a>
                <a href="{{ route('competitions.schedule') }}">Zawody</a>
                @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Panel administracyjny</a>
                @endif
                @endauth
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </nav>
    </header>

    <div class="main-container">
        <div class="content">
            @yield('content')
        </div>
    </div>

    <footer>
        <div class="quote-container">
            <blockquote>
                <p>{{ $quote->quote ?? 'Inspirational quote goes here.' }}</p>
                <footer>- {{ $quote->author ?? 'Author Name' }}</footer>
            </blockquote>
        </div>
        <p>&copy; 2024 Dancipline. All rights reserved.</p>
    </footer>
    @yield('scripts')
</body>
</html>
