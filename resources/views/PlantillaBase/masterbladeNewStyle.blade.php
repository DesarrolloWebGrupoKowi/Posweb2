<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/logokowi.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">
    <link href="{{ asset('material-icon/material-icon.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Icons/font-awesome.min.css') }}">
    <title>@yield('title')</title>
</head>

<body>

    <body>
        <nav class="navbar navbar-expand navbar-dark" style="background: #1e293b">
            <div class="container-fluid @yield('dashboardWidth')">
                <a id="imgLogo" class="navbar-brand" href="/"><img
                        src="https://www.kowi.com.mx/wp-content/uploads/elementor/thumbs/01_LOGO_KOWI-pdb6yay1990vudjiiivqobds1rhtcw2u1qinecxwpy.png"
                        class="rounded" height="32"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
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
                                    <span class="material-icons pe-1 text-white"
                                        style="font-size: 2.5rem">account_circle</span>
                                    <span class="pe-1" style="line-height: 1rem">
                                        {{ strtoupper(Auth::user()->NomUsuario) }} <br>
                                        <small>{{ Auth::user()->tipoUsuario->NomTipoUsuario }}</small>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownUser1"
                                    style="background: #1e293b">
                                    @if (!request()->routeIs('login'))
                                        <li>
                                            <a href="/MiPerfil" class="dropdown-item text-white">
                                                <i class="fa fa-user-circle"></i> Mi Perfil
                                            </a>
                                        </li>
                                    @endif

                                    @if (!request()->routeIs('dashboard'))
                                        <li>
                                            <a href="/Dashboard" class="dropdown-item text-white">
                                                <i class="fa fa-bars"></i> Dashboard
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item text-white" href="/Logout"
                                            onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out"></i> Cerrar Sesión
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
        <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
        @yield('contenido')

        <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>

        @yield('scriptTiendas')
        <script src="{{ asset('js/tiendasScript.js') }}"></script>
    </body>

</html>
