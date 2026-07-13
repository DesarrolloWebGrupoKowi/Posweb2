<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <meta
        name="employee-name"
        content="{{ Auth::user()->EmployeeName ?? '' }}"
    >
    <title>@yield('title') - Posweb</title>

    <link
        rel="shortcut icon"
        href="{{ asset('img/logokowi-v2.png') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('Icons/font-awesome.min.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('css/typeTailwind.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('Icons/bootstrap-icons.css') }}"
    >

    @yield('styles')

    <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
</head>

<body>
    {{-- <body style="background-color: #f8fafc;"> --}}
    @guest
        <nav
            class="navbar navbar-expand navbar-dark"
            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); width: 100%; z-index: 999;"
        >
            <!-- Nav para usuarios no autenticados -->
            <div class="container-fluid @yield('dashboardWidth')">
                <a
                    id="imgLogo"
                    class="navbar-brand"
                    href="/"
                >
                    <img
                        src={{ asset('img/logo-v2.png') }}
                        class="rounded"
                        height="32"
                    >
                </a>
                @if (!request()->routeIs('login'))
                    <a
                        class="nav-link d-flex align-items-center text-decoration-none text-white"
                        href="Login"
                    >
                        <span class="pe-2">@include('components.icons.user')</span>
                        <strong>Iniciar Sesión</strong>
                    </a>
                @endif
            </div>
        </nav>
        <div style="padding-top: 59px">
            @yield('contenido')
        </div>
    @else
        @if (request()->routeIs('dashboard'))
            <!-- En el dashboard: NO mostrar nav separado, se integra en el header -->
            @yield('contenido')
        @else
            <!-- En otras páginas: Nav sticky full-width -->
            <div
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                        width: 100%;
                        position: sticky;
                        top: 0;
                        z-index: 1030;
                        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);">
                <div class="px-4 py-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-4">
                            <a
                                class="flex-shrink-0 overflow-hidden"
                                href="/"
                            >
                                <img
                                    src="{{ asset('img/logo-v2.png') }}"
                                    class="rounded"
                                    height="32"
                                    alt="Logo"
                                >
                            </a>
                        </div>
                        <div
                            id="ddUsuario"
                            class="dropdown"
                        >
                            <a
                                href="#"
                                class="d-flex align-items-center text-decoration-none gap-2 text-white"
                                id="dropdownUser1"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <div
                                    class="rounded-circle flex-shrink-0 overflow-hidden"
                                    style="width: 36px; height: 36px; border: 2px solid rgba(255,255,255,0.4);"
                                >
                                    <img
                                        src="{{ asset('img/logocaricatura.webp') }}"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                        alt="Logo"
                                    >
                                </div>
                                <span
                                    class="pe-1"
                                    style="line-height: 1rem; font-weight: 500; color: white"
                                >
                                    {{ strtoupper(Auth::user()->NomUsuario) }} <br>
                                    <small
                                        style="color: rgba(255,255,255,0.7)">{{ Auth::user()->tipoUsuario->NomTipoUsuario }}</small>
                                </span>
                            </a>
                            <ul
                                class="dropdown-menu"
                                aria-labelledby="dropdownUser1"
                                style="min-width: 200px;"
                            >
                                @if (!request()->routeIs('dashboard'))
                                    <li>
                                        <a
                                            href="/Dashboard"
                                            class="dropdown-item"
                                        >
                                            @include('components.icons.tools') Dashboard
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->tipoUsuario->IdTipoUsuario == 2)
                                    <li>
                                        <a
                                            href="/ActualizacionPrecios"
                                            class="dropdown-item"
                                        >
                                            @include('components.icons.tools') Admin Scale
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="/Update"
                                            class="dropdown-item"
                                        >
                                            @include('components.icons.sync') Sincronizar datos
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="/Logout"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    >
                                        @include('components.icons.logout') Cerrar sesión
                                    </a>
                                    <form
                                        id="logout-form"
                                        action="/Logout"
                                        method="POST"
                                        class="d-none"
                                    >
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                @yield('contenido')
            </div>
        @endif
    @endguest

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    @yield('scripts')
</body>

</html>
