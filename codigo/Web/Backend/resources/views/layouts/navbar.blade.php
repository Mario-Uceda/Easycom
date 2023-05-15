<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css"/>
    <title>Easycom</title>
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <div class="container-fluid p-0">
      <!-- Inicio Navbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">
            <img src="../images/IconApp.png" alt="" width="30" height="30" class="d-inline-block align-text-top">
            Easycom
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/">Inicio</a>
              </li>
              <!-- Si el usuario esta logeado -->
@auth
              <li class="nav-item">
                <a class="nav-link" href="/historial">Historial</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/favoritos">Favoritos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/notificaciones">Notificaciones</a>
              </li>
            </ul>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <form style="display: inline" action="/logout" method="POST">
                  @csrf
                  <a class="nav-link" href="#" onclick="this.closest('form').submit()">Logout</a>
                </form>
              </li>
            </ul>
            <!-- Si el usuario no está logueado -->
@else
            </ul>  
            <ul class="navbar-nav ml-auto">
              <li class="nav-item d-flex">
                <a class="nav-link" href="/login">Login</a>
              </li>
              <li class="nav-item d-flex">
                <a class="nav-link" href="/register">Registro</a>
              </li>
            </ul>
@endauth
          </div>
        </div>
      </nav>
    </div>
    @if(session('status'))
      <br>
        {{ session('status') }}
    @endif

    <!-- Fin Navbar -->
    
    <!-- Inicio Contenido Principal -->
    
    @yield('contenidoPrincipal')
    
    <!-- Fin Contenido Principal -->
  </body>
</html>

<script>
  // Obtener la URL actual
  var url = window.location.href;

  // Obtener todos los elementos de enlace de la barra de navegación
  var links = document.querySelectorAll('.nav-link');

  // Recorrer todos los enlaces y verificar si la URL actual coincide con el enlace
  for (var i = 0; i < links.length; i++) {
    if (links[i].href === url) {
      // Si la URL coincide, agregar la clase "active" al enlace
      links[i].classList.add('active');
    }
  }
</script>
