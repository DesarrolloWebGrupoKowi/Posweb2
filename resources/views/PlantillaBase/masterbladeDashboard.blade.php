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

<body class="@yield('bodyTheme')">

    <!-- ============================================================ -->
    <!-- NAVBAR UNIFICADA - Mismo estilo para todos -->
    <!-- ============================================================ -->
    @if (!request()->routeIs('dashboard'))
        <nav
            style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
                width: 100%;
                position: sticky;
                top: 0;
                z-index: 1030;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);">
            <div class="px-4 py-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                    <!-- Logo y botón de tema -->
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
                        @auth
                            <button
                                id="btnThemeToggle"
                                class="btn btn-sm"
                                style="background: rgba(255,255,255,0.15); color: white; border: none; border-radius: 8px; padding: 6px 10px; line-height: 1;"
                                title="Cambiar tema"
                            >
                                <i class="bi bi-palette"></i>
                            </button>
                        @endauth
                    </div>

                    <!-- Lado derecho: Usuario o Login -->
                    <div>
                        @guest
                            <a
                                href="{{ url('/Login') }}"
                                class="d-flex align-items-center text-decoration-none gap-2 text-white"
                                style="font-weight: 500; padding: 6px 16px; border-radius: 8px; background: rgba(255,255,255,0.12); transition: all 0.2s;"
                            >
                                <i
                                    class="bi bi-box-arrow-in-right"
                                    style="font-size: 1.1rem;"
                                ></i>
                                <span>Iniciar Sesión</span>
                            </a>
                        @else
                            <!-- Dropdown de usuario (igual que antes) -->
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
                                            src="{{ asset('img/logocaricatura.jpg') }}"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            alt="Avatar"
                                        >
                                    </div>
                                    <span style="line-height: 1rem; font-weight: 500; color: white">
                                        {{ strtoupper(Auth::user()->NomUsuario) }} <br>
                                        <small
                                            style="color: rgba(255,255,255,0.7)">{{ Auth::user()->tipoUsuario->NomTipoUsuario ?? 'Usuario' }}</small>
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
                                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->tipoUsuario->IdTipoUsuario == 2 ?? false)
                                        <li>
                                            <a
                                                href="/ActualizacionPrecios"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-tools me-2"></i> Admin Scale
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="/Update"
                                                class="dropdown-item"
                                            >
                                                <i class="bi bi-arrow-repeat me-2"></i> Sincronizar datos
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
                                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
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
                        @endguest
                    </div>

                </div>
            </div>
        </nav>
    @endif

    <!-- ============================================================ -->
    <!-- CONTENIDO -->
    <!-- ============================================================ -->
    <div @auth style="padding-top: 0;" @else style="padding-top: 0;" @endauth>
        @yield('contenido')
    </div>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var themes = ['', 'theme-highcontrast'];
            var icons = ['bi-palette', 'bi-brush'];
            var hasServerTheme = document.body.classList.contains('theme-highcontrast');
            var saved = localStorage.getItem('theme') || '';
            if (saved && !hasServerTheme) document.body.classList.add(saved);

            var btn = document.getElementById('btnThemeToggle');
            if (btn) {
                var idx = themes.indexOf(saved);
                if (idx === -1) idx = 0;
                btn.querySelector('i').className = 'bi ' + icons[idx];

                btn.addEventListener('click', function() {
                    themes.forEach(function(t) {
                        if (t) document.body.classList.remove(t);
                    });
                    idx = (idx + 1) % themes.length;
                    var next = themes[idx];
                    if (next) document.body.classList.add(next);
                    localStorage.setItem('theme', next);
                    btn.querySelector('i').className = 'bi ' + icons[idx];
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
