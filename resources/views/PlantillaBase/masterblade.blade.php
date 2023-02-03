<!DOCTYPE html>
<html lang="es">
<style>
    .material-icons:active{
        transform: scale(1.5);
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logokowi.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Style.css') }}">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Icons/font-awesome.min.css') }}">
    <title>@yield('title')</title>
</head>

<body>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a id="imgLogo" class="navbar-brand" href="/"><img src="{{ asset('img/logokowi.png') }}"
                        class="rounded" width="30" height="30"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto me-5">
                        @guest
                            <li id="ddLogin" class="nav-item dropdown">
                                <a class="nav-link d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                    href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <strong>Iniciar Sesión</strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                    aria-labelledby="navbarDropdownMenuLink">
                                    <li>
                                        <a class="dropdown-item" href="/Login"><i class="fa fa-sign-in"></i> Login</a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <div id="ddUsuario" class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-icons" style="color: white">account_circle</span>
                                    <strong>{{ Auth::user()->NomUsuario }}</strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownUser1">
                                    <a class="dropdown-item disabled"><i class="fa fa-id-card-o"></i>
                                        {{ Auth::user()->tipoUsuario->NomTipoUsuario }}</a>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a href="/MiPerfil" class="dropdown-item"><i class="fa fa-user"></i> Mi Perfil</a>
                                    </li>
                                    <li>
                                        <a href="/Dashboard" class="dropdown-item"><i class="fa fa-bars"></i> Dashboard</a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li>
                                        <a class="dropdown-item" href="/Logout"
                                            onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"><i
                                                class="fa fa-sign-out"></i> Cerrar
                                            Sesión</a>
                                        </a>
                                        <form id="logout-form" action="/Logout" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div>
            @if (request()->routeIs('index') or request()->routeIs('login') or request()->routeIs('dashboard'))
            @else
                <a class="bAtras" style="margin-left: 10px;" href="/Dashboard">
                    <span style="font-size: 28px; color:black" class="material-icons my-2 card shadow bg-warning">keyboard_return</span>
                </a>
            @endif
        </div>
        <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
        @yield('contenido')

        <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        @yield('scriptTiendas')
        <script src="{{ asset('js/tiendasScript.js') }}"></script>
    </body>

</html>
