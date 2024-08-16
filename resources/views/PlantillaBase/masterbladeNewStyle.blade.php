<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - Posweb </title>

    <link rel="shortcut icon" href="{{ asset('img/logokowi-v2.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('material-icon/material-icon.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('Icons/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">

    <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark"
        style="background: #1e293b; position: fixed; width: 100%; z-index: 999;">
        <div class="container-fluid @yield('dashboardWidth')">
            <a id="imgLogo" class="navbar-brand" href="/">
                <img src={{ asset('img/logo-v2.png') }} class="rounded" height="32">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            @guest
                @if (!request()->routeIs('login'))
                    <a class="nav-link d-flex align-items-center text-white text-decoration-none" href="Login">
                        <span class="pe-2">@include('components.icons.user')</span>
                        <strong>Iniciar Sesión</strong>
                    </a>
                @endif
            @else
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        <div id="ddUsuario" class="dropdown">
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none gap-2"
                                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="font-size: 1.5rem; background: #e2e8f0; width: 2.5rem; height: 2.5rem; color: #1e293b; font-family: 'Times New Roman', serif; text-align: center; cursor: pointer;">
                                    {{ strtoupper(substr(Auth::user()->NomUsuario, 0, 1)) }}
                                </span>
                                <span class="pe-1" style="line-height: 1rem; font-weight: 500; color: #e2e8f0">
                                    {{ strtoupper(Auth::user()->NomUsuario) }} <br>
                                    <small>{{ Auth::user()->tipoUsuario->NomTipoUsuario }}</small>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownUser1"
                                style="background: #1e293b; border-radius: 12px; width: 200px;">
                                @if (!request()->routeIs('miperfil'))
                                    {{-- <li>
                                        <a href="/MiPerfil" class="dropdown-item text-white py-2">
                                            @include('components.icons.user') Mi Perfil
                                        </a>
                                    </li> --}}
                                @endif

                                @if (!request()->routeIs('dashboard'))
                                    <li>
                                        <a href="/Dashboard" class="dropdown-item text-white py-2">
                                            @include('components.icons.bars') Dashboard
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->tipoUsuario->IdTipoUsuario == 2)
                                    <li>
                                        <a href="/ActualizacionPrecios" class="dropdown-item text-white py-2">
                                            @include('components.icons.upload') Admin Scale
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/Update" class="dropdown-item text-white py-2">
                                            @include('components.icons.switch') Sincronizar datos
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item text-white py-2" href="/Logout"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        @include('components.icons.logout') Cerrar Sesión
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

    <div class="mb-4" style="padding-top: 59px">
        @if (!request()->routeIs('dashboard') && Auth::user()->tipoUsuario->IdTipoUsuario == 2)
            <div style="background: #cbd5e1">
                <div class="container-fluid @yield('dashboardWidth')">
                    <a href="/Dashboard" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.tools')Dashboard
                    </a>
                    <a href="/Pos" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.check')Punto de venta
                    </a>
                    <a href="/CorteDiario" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.list')Corte diario
                    </a>
                    <a href="/VentaTicketDiario"
                        class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.check-all')Tickets
                    </a>
                    <a href="/SolicitudFactura" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.cloud-check')Facturación
                    </a>
                    <a href="/SolicitudCancelacionTicket"
                        class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.x')Cancelación
                    </a>
                    <a href="/RecepcionProducto"
                        class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.bars')Recepción
                    </a>
                    <a href="/TransaccionProducto"
                        class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.switch')Transsacciones
                    </a>
                    <a href="/CapMermas" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.down')Mermas
                    </a>
                    <a href="/Pedidos" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.cart')Pedidos
                    </a>
                    <a href="/AsignarPreparados"
                        class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.text-file')Preparados
                    </a>
                    <a href="/VerRosticero" class="btn btn-primary btn-sm rounded-pill py-0 bg-transparent border-0"
                        style="color: #1e293b; font-weight: 500">
                        @include('components.icons.next')Rosticero
                    </a>
                </div>
            </div>
        @endif
        @yield('contenido')
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>

    @yield('scripts')
</body>

</html>
