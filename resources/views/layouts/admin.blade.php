<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('custom_styles')
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #1f1f1f;
        }
        .navbar-brand {
            color: #fff;
        }
        .navbar-brand:hover {
            color: #ffc107;
        }
        .sidebar {
            position: fixed; 
            height: 100vh;
            width: 220px;
            background-color: #1f1f1f;
            padding-top: 20px;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            white-space: nowrap;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: #ffc107;
        }
        .content {
            padding: 20px;
            margin-left:220px; 
        }
        .content h1 {
            margin-top: 0;
        }
 .button-27-icon {
  appearance: none;
  background-color: #1f1f1f;
  border: 2px solid #1A1A1A;
  border-radius: 8px;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-flex; /* Ustawia przycisk jako elastyczny kontener */
  align-items: center; /* Wyrównanie ikon w pionie */
  justify-content: center; /* Wyrównanie ikon w poziomie */
  font-family: 'Roobert', -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
  font-size: 16px;
  padding: 10px; /* Zmniejszenie paddingu */
  width: auto; /* Ustawienie szerokości przycisku zgodnie z treścią */
  margin-right: 5px;
  margin-bottom: 5px; /* Odstęp między przyciskami */
  transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-27-icon:hover {
  box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
  transform: translateY(-2px);
  color: #ffc107;
}
.btn-outline-light:hover{
    background-color: #ffc107;
}

.button-27-icon:active {
  box-shadow: none;
  transform: translateY(0);
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Panel administracyjny</a>
        <div class="ml-auto">
            <a href="{{ route('training_systems.index') }}" class="btn btn-outline-light">Strona główna</a>
        </div>
    </nav>

    <div class="d-flex">>
        <div class="sidebar">
            <h4 class="text-center">Menu</h4>
            <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> Użytkowiki</a>
            <a href="{{ route('admin.dance_legends.index') }}"><i class="fas fa-star"></i> Taneczne legendy</a>
            <a href="{{ route('admin.tutorials.index') }}"><i class="fas fa-chalkboard-teacher"></i> Tutoriale</a>
            <a href="{{ route('admin.quotes.index') }}"><i class="fas fa-pen"></i> Cytaty</a>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
