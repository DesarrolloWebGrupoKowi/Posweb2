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

    <style>
        .dropdown-menu[data-bs-popper] {
            right: 0;
            left: auto;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand navbar-dark"
        style="background: #1e293b; position: fixed; width: 100%; z-index: 999;">
        <div class="container-fluid @yield('dashboardWidth')">
            <a id="imgLogo" class="navbar-brand" href="/"><img
                    src="https://www.kowi.com.mx/wp-content/uploads/elementor/thumbs/01_LOGO_KOWI-pdb6yay1990vudjiiivqobds1rhtcw2u1qinecxwpy.png"
                    class="rounded" height="32"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            @guest
                @if (!request()->routeIs('login'))
                    <a class="nav-link d-flex align-items-center text-white text-decoration-none" href="Login">
                        <i class="fa fa-user pe-2" style="font-size: 1.2rem"></i>
                        <strong>Iniciar Sesión</strong>
                    </a>
                @endif
            @else
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
                        <div id="ddUsuario" class="dropdown">
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none"
                                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons pe-1 text-white" style="font-size: 2.5rem">account_circle</span>
                                <span class="pe-1" style="line-height: 1rem">
                                    {{ strtoupper(Auth::user()->NomUsuario) }} <br>
                                    <small>{{ Auth::user()->tipoUsuario->NomTipoUsuario }}</small>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownUser1"
                                style="background: #1e293b; border-radius: 12px; width: 200px;">
                                @if (!request()->routeIs('miperfil'))
                                    <li>
                                        <a href="/MiPerfil" class="dropdown-item text-white py-2">
                                            <i class="fa fa-user-circle pe-2"></i> Mi Perfil
                                        </a>
                                    </li>
                                @endif

                                @if (!request()->routeIs('dashboard'))
                                    <li>
                                        <a href="/Dashboard" class="dropdown-item text-white py-2">
                                            <i class="fa fa-bars pe-2"></i> Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="/Update" class="dropdown-item text-white py-2">
                                        <i class="fa fa-cloud-download pe-2"></i> Actualizar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-white py-2" href="/Logout"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out pe-2"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="/Logout" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </ul>
                </div>
            @endguest
        </div>
    </nav>
    <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
    <div class="mb-4" style="padding-top: 64px">
        @yield('contenido')
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @yield('scriptTiendas')
    <script src="{{ asset('js/tiendasScript.js') }}"></script>
</body>

</html>
