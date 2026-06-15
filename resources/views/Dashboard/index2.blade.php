@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Dashboard PosWeb2')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <!-- Navbar Full Width - Pegado arriba y de lado a lado -->
    <div
        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;">
        <!-- Nav integrado -->
        <div class="px-4 py-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a
                    class="navbar-brand"
                    href="/"
                >
                    <img
                        src="{{ asset('img/logo-v2.png') }}"
                        class="rounded"
                        height="32"
                        alt="Logo"
                    >
                </a>
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
                        <span
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="font-size: 1.5rem; background: rgba(255,255,255,0.2); width: 2.5rem; height: 2.5rem; color: white; font-family: 'Times New Roman', serif; text-align: center; cursor: pointer;"
                        >
                            {{ strtoupper(substr(Auth::user()->NomUsuario, 0, 1)) }}
                        </span>
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
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif
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

        <!-- Separador sutil -->
        <hr
            class="mx-4 my-2"
            style="border-color: rgba(255,255,255,0.1);"
        >

        <!-- Información del usuario y fecha -->
        <div class="px-4 pb-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div
                    class="rounded-circle d-flex align-items-center justify-content-center"
                    style="background-color: rgba(255, 255, 255, 0.15); width: 50px; height: 50px;"
                >
                    <x-icons.store
                        width="20"
                        height="20"
                        color="#fff"
                    />
                </div>
                <div>
                    <h2
                        class="mb-0 text-white"
                        style="font-weight: 600; font-size: 1.5rem;"
                    >Mi Punto de Venta</h2>
                    <p
                        class="text-white-50 mb-0"
                        style="font-size: 0.85rem;"
                    >
                        @php
                            $hora = date('H');
                            $saludo =
                                $hora >= 6 && $hora < 12
                                    ? 'Buenos días'
                                    : ($hora >= 12 && $hora < 19
                                        ? 'Buenas tardes'
                                        : 'Buenas noches');
                        @endphp
                        {{ $saludo }}, {{ Auth::user()->Empleado->Nombre }} {{ Auth::user()->Empleado->Apellidos }}
                    </p>
                </div>
                <div class="ms-auto">
                    <div class="text-end">
                        <p class="text-white-50 small mb-0">Fecha actual</p>
                        <p
                            class="fw-semibold mb-0 text-white"
                            style="font-size: 0.9rem;"
                        >
                            {{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del Dashboard con ancho normal -->
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <!-- Tarjeta Principal del Dashboard -->
        <div
            class="card border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Alertas -->
            <div class="px-4 pt-4">
                @include('Alertas.Alertas')
            </div>

            <!-- Resto del dashboard (menús, etc.) -->
            @if ($menus->count() == 0)
                <div class="p-5 text-center">
                    <div
                        class="d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 80px; height: 80px; background-color: rgba(220, 38, 38, 0.1); border-radius: 50%;"
                    >
                        <i
                            class="fas fa-exclamation-triangle"
                            style="font-size: 36px; color: #dc2626;"
                        ></i>
                    </div>
                    <h4 class="mb-2 text-gray-800">Menú no encontrado</h4>
                    <p class="text-muted mb-0">
                        Este usuario no cuenta con menús asignados. Favor de hablar con el administrador.
                    </p>
                </div>
            @else
                <!-- Menú Horizontal - Pestañas con línea inferior -->
                <div class="px-4 pt-2">
                    <!-- Línea contenedora del menú -->
                    <div
                        class="border-bottom"
                        style="border-color: #e5e7eb !important;"
                    >
                        <ul
                            class="nav nav-tabs mb-0 gap-2 border-0"
                            id="menuTabs"
                            role="tablist"
                        >
                            @foreach ($menus as $index => $headerMenu)
                                <li
                                    class="nav-item"
                                    role="presentation"
                                >
                                    <button
                                        class="nav-link {{ $index == 0 ? 'active' : '' }} rounded-top-3"
                                        style="border: none; padding: 10px 24px; font-weight: 500; transition: all 0.2s ease; position: relative;"
                                        data-bs-toggle="tab"
                                        data-bs-target="#tab{{ $loop->index }}"
                                        type="button"
                                        role="tab"
                                    >
                                        {{ ucfirst(mb_strtolower($headerMenu->NomTipoMenu, 'UTF-8')) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-4">
                        @foreach ($menus as $index => $headerMenu)
                            <div
                                class="tab-pane {{ $index == 0 ? 'show active' : '' }}"
                                id="tab{{ $loop->index }}"
                                role="tabpanel"
                            >
                                <!---Card Horizontal - Material Design-->
                                {{-- <div class="row g-3 pb-4">
                                    @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                                        @php
                                            $colorMap = [
                                                'bg-blue' => 'linear-gradient(135deg, #93c5fd 0%, #60a5fa 100%)',
                                                'bg-green' => 'linear-gradient(135deg, #86efac 0%, #4ade80 100%)',
                                                'bg-orange' => 'linear-gradient(135deg, #fed7aa 0%, #fdba74 100%)',
                                                'bg-red' => 'linear-gradient(135deg, #fecaca 0%, #fca5a5 100%)',
                                                'bg-warning' => 'linear-gradient(135deg, #fde68a 0%, #fcd34d 100%)',
                                                'bg-purple' => 'linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%)',
                                                'bg-pink' => 'linear-gradient(135deg, #fbcfe8 0%, #f9a8d4 100%)',
                                                'bg-teal' => 'linear-gradient(135deg, #99f6e4 0%, #5eead4 100%)',
                                                'bg-indigo' => 'linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%)',
                                                'bg-cyan' => 'linear-gradient(135deg, #a5f3fc 0%, #67e8f9 100%)',
                                                'bg-gray' => 'linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%)',
                                            ];
                                            $bgColor = $detalleMenu->PivotMenu->BgColor ?? 'bg-gray';
                                            $gradient = $colorMap[$bgColor] ?? 'linear-gradient(135deg, #9ca3af 0%, #6b7280 100%)';
                                        @endphp
                                        <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
                                            <a href="{{ $detalleMenu->PivotMenu->Link }}" class="text-decoration-none d-block menu-item">
                                                <div class="card h-100 border-0 shadow-sm"
                                                    style="border-radius: 12px; transition: all 0.2s ease; cursor: pointer; background: white;"
                                                    onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                                style="width: 48px; height: 48px; background: {{ $gradient }};">
                                                                <i class="{{ $detalleMenu->PivotMenu->Icono }}" style="font-size: 22px; color: white;"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-semibold mb-0 text-gray-800">
                                                                    {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div> --}}

                                <div class="row g-3 pb-4">
                                    @foreach ($headerMenu->DetalleMenu as $detalleMenu)
                                        @php
                                            $iconColors = [
                                                'bg-blue' => '#3b82f6',
                                                'bg-green' => '#10b981',
                                                'bg-orange' => '#f97316',
                                                'bg-red' => '#ef4444',
                                                'bg-purple' => '#8b5cf6',
                                                'bg-teal' => '#14b8a6',
                                                'bg-indigo' => '#6366f1',
                                                'bg-cyan' => '#06b6d4',
                                                'bg-pink' => '#ec4899',
                                                'bg-gray' => '#64748b',
                                            ];
                                            $bgColor = $detalleMenu->PivotMenu->BgColor ?? 'bg-gray';
                                            $iconColor = $iconColors[$bgColor] ?? '#64748b';
                                        @endphp
                                        <div class="col-xxl-2 col-lg-3 col-md-4 col-6">
                                            <a
                                                href="{{ $detalleMenu->PivotMenu->Link }}"
                                                class="text-decoration-none d-block menu-item"
                                            >
                                                <div
                                                    class="text-center"
                                                    style="transition: all 0.25s ease; cursor: pointer;"
                                                    onmouseover="this.style.transform='translateY(-8px)'"
                                                    onmouseout="this.style.transform='translateY(0)'"
                                                >
                                                    <div
                                                        class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                        style="width: 72px; height: 72px; background: white; box-shadow: 0 8px 24px {{ $iconColor }}20; transition: all 0.25s ease;"
                                                        onmouseover="this.style.boxShadow='0 12px 32px {{ $iconColor }}40'"
                                                        onmouseout="this.style.boxShadow='0 8px 24px {{ $iconColor }}20'"
                                                    >
                                                        <i
                                                            class="{{ $detalleMenu->PivotMenu->Icono }}"
                                                            style="font-size: 28px; color: {{ $iconColor }};"
                                                        ></i>
                                                    </div>
                                                    <h6
                                                        class="fw-semibold mb-0"
                                                        style="color: #475569; font-size: 0.8rem;"
                                                    >
                                                        {{ ucfirst(mb_strtolower($detalleMenu->PivotMenu->NomMenu, 'UTF-8')) }}
                                                    </h6>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Estilos para las pestañas - Mejor distinción del seleccionado */
        .nav-tabs {
            border-bottom: none !important;
        }

        .nav-tabs .nav-link {
            background: transparent;
            color: #6b7280;
            border-radius: 8px 8px 0 0 !important;
            position: relative;
            margin-bottom: -1px;
        }

        /* Estilo para pestaña inactiva */
        .nav-tabs .nav-link:not(.active) {
            background: #f3f4f6;
            color: #4b5563;
        }

        .nav-tabs .nav-link:not(.active):hover {
            background: #e5e7eb;
            color: #1f2937;
            transform: translateY(-2px);
        }

        /* Estilo para pestaña activa - MUY DISTINTIVA */
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white !important;
            font-weight: 600;
        }

        /* Línea indicadora debajo de la pestaña activa */
        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: #f59e0b;
            border-radius: 3px 3px 0 0;
        }

        /* Sombra en la pestaña activa */
        .nav-tabs .nav-link.active {
            box-shadow: 0 -2px 8px rgba(30, 41, 59, 0.15);
        }

        /* Línea base del menú */
        .border-bottom {
            border-bottom-width: 2px !important;
        }

        /* Efecto hover en los items del menú */
        .menu-item {
            transition: all 0.2s ease;
        }

        /* Scroll suave */
        html {
            scroll-behavior: smooth;
        }

        /* Animación de fade para las pestañas */
        .tab-pane {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Eliminar overflow horizontal */
        body {
            overflow-x: hidden;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .nav-tabs::-webkit-scrollbar {
                height: 3px;
            }

            .nav-tabs::-webkit-scrollbar-track {
                background: #e2e8f0;
                border-radius: 10px;
            }

            .nav-tabs::-webkit-scrollbar-thumb {
                background: #94a3b8;
                border-radius: 10px;
            }
        }
    </style>

    <script>
        // Guardar la pestaña activa en localStorage
        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar la última pestaña activa
            const activeTab = localStorage.getItem('activeMenuTab');
            if (activeTab) {
                const tabButton = document.querySelector(`.nav-link[data-bs-target="${activeTab}"]`);
                if (tabButton) {
                    const tab = new bootstrap.Tab(tabButton);
                    tab.show();
                }
            }

            // Guardar la pestaña activa cuando se cambia
            const tabButtons = document.querySelectorAll('.nav-link');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function() {
                    const target = this.getAttribute('data-bs-target');
                    localStorage.setItem('activeMenuTab', target);
                });
            });
        });
    </script>
@endsection
