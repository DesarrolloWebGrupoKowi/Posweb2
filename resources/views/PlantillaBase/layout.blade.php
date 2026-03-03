<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Posweb </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('img/logokowi-v2.png') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Icons/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/typeTailwind.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    @yield('styles')

    <script src="{{ asset('JQuery/jquery-3.6.0.min.js') }}"></script>
    <style>
        body,
        button,
        input,
        a,
        div,
        p,
        span,
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Inter', sans-serif !important;
        }

        .menu-title {
            display: block;
            font-size: 13px;
            color: #6b7280;
            /* color: oklch(92.8% 0.006 264.531); */
            /* gris Tailwind */
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 0px;
            margin-bottom: 8px;
        }

        /* ESTILO DE LOS MENÚS FIJOS */
        #sidebar-left,
        #sidebar-right {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            /* background: #f8f9fa; */
            background: white;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            transition: transform .3s ease;
            z-index: 1000;
        }

        #sidebar-left .list-group-item {
            border: none;
            padding: 12px 14px;
            font-size: 15px;
            /* font-weight: 500;
            color: #374151; */
            font-weight: 500;
            color: oklch(55.1% 0.027 264.364);
            border-radius: 8px;
            margin-bottom: 4px;
            transition: background .2s;
        }

        #sidebar-left .list-group-item:hover {
            background: #e5e7eb;
            color: #374151;
        }

        #sidebar-left .list-group-item i {
            font-size: 18px;
            vertical-align: middle;
        }

        #sidebar-left ul .list-group-item {
            background: transparent;
            color: #4b5563;
            font-size: 14px;
        }

        #sidebar-left ul.list-group .list-group-item {
            background: #f8f9fa;
            border: none;
            font-size: 14px;
        }

        #sidebar-left ul.list-group .list-group-item:hover {
            background: #dee2e6;
        }

        .sub-item {
            font-weight: 600;
            padding-left: 10px;
            cursor: pointer;
        }

        #sidebar-left .list-group-item {
            border: none;
            padding: 10px 12px;
        }

        #sidebar-left .list-group-item:hover {
            background: #f1f1f1;
        }


        #sidebar-right {
            right: 0;
            border-left: 1px solid #ddd;
        }

        /* OCULTAR VIA TRANSFORM */
        .collapsed-left {
            transform: translateX(-250px);
        }

        .collapsed-right {
            transform: translateX(250px);
        }

        /* CONTENIDO CENTRAL AJUSTADO */
        #main-content {
            margin-left: 250px;
            margin-right: 250px;
            transition: margin .3s ease;
        }

        /* AJUSTAR CUANDO ESTÉN COLAPSADOS */
        .no-left {
            margin-left: 0 !important;
        }

        .no-right {
            margin-right: 0 !important;
        }
    </style>

    <style>
        .logo-text {
            font-family: 'Baloo 2', cursive !important;
            letter-spacing: 1.5px;
            font-size: 32px;
            color: #1e293b;
        }

        .logo-text::first-letter {
            font-size: 36px;
            line-height: 32px
        }
    </style>

</head>

<body>

    <!-- ────────────────────────────────────────────── -->
    <!-- 📌 MENÚ LATERAL IZQUIERDO -->
    <!-- ────────────────────────────────────────────── -->
    {{-- <div id="sidebar-left">
        <div class="p-3 text-center border-bottom">
            <img src="{{ asset('img/logokowi-v2.png') }}" alt="Logo" style="width: 120px; height: auto;">
        </div>

        <div class="p-3">

            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-shop me-2"></i> Tiendas
            </a>

            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-people me-2"></i> Empleados
            </a>

            <!-- 📁 Catálogos -->
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#menuCatalogos">
                <span><i class="bi bi-folder me-2"></i> Catálogos</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="menuCatalogos">
                <ul class="list-group mt-2">
                    <li class="list-group-item">Productos</li>
                    <li class="list-group-item">Marcas</li>
                    <li class="list-group-item">Categorías</li>
                    <li class="list-group-item">Subcategorías</li>
                    <li class="list-group-item">Unidades</li>
                </ul>
            </div>

            <!-- 📦 Inventario -->
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#menuInventario">
                <span><i class="bi bi-box-seam me-2"></i> Inventario</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="menuInventario">
                <ul class="list-group mt-2">
                    <li class="list-group-item">Entradas</li>
                    <li class="list-group-item">Salidas</li>
                    <li class="list-group-item">Ajustes</li>
                    <li class="list-group-item">Traspasos</li>
                </ul>
            </div>

            <!-- 📊 Reportes -->
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#menuReportes">
                <span><i class="bi bi-bar-chart me-2"></i> Reportes</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse ps-3" id="menuReportes">
                <ul class="list-group mt-2">
                    <li class="list-group-item">Ventas</li>
                    <li class="list-group-item">Existencias</li>
                    <li class="list-group-item">Movimientos</li>
                    <li class="list-group-item">Auditoría</li>
                </ul>
            </div>

            <a href="#" class="list-group-item list-group-item-action">
                <i class="bi bi-diagram-2 me-2"></i> Interfaces
            </a>

            <div class="mt-4">
                <button class="btn btn-sm btn-danger w-100" id="toggleLeft">
                    <i class="bi bi-chevron-left"></i> Ocultar Menú
                </button>
            </div>

        </div>
    </div> --}}

    <div id="sidebar-left">
        <a class="m-0 p-3 pt-4 list-group-item d-flex align-items-end">
            <img src="{{ asset('img/logokowi-v2.png') }}" alt="Logo" style="width: 48px;">
            <span class="ms-2 logo-text">Kowi</span>
        </a>


        <div class="p-3">

            <!-- ======================================================= -->
            <!-- OPCIONES PRINCIPALES                                   -->
            <!-- ======================================================= -->

            <span href="Dashboard" class="menu-title">DASHBOARDS</span>
            <!-- Dashboard -->
            <a href="home" class="list-group-item list-group-item-action">
                <i class="bi bi-speedometer2 me-2"></i> Ventas
            </a>

            <!-- Tiendas -->
            <a class="list-group-item list-group-item-action">
                <i class="bi bi-shop me-2"></i> Tiendas
            </a>

            <!-- Empleados -->
            <a class="list-group-item list-group-item-action">
                <i class="bi bi-people me-2"></i> Empleados
            </a>

            <!-- ======================================================= -->
            <!-- 1. CATÁLOGOS (Principal)                                -->
            <!-- ======================================================= -->
            <a href="Dashboard" class="menu-title">Administración</a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#cat_master">
                <span><i class="bi bi-folder me-2"></i> Catálogos</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div class="collapse" id="cat_master">

                <!-- Productos & Artículos -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#cat_productos">
                    Productos & Artículos <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="cat_productos">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Artículos</li>
                        <li class="list-group-item">Tipos de artículo</li>
                        <li class="list-group-item">Familias</li>
                        <li class="list-group-item">Grupos</li>
                        <li class="list-group-item">Movimientos de producto</li>
                        <li class="list-group-item">Paquetes</li>
                    </ul>
                </div>

                <!-- Precios & Pagos -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#cat_precios">
                    Precios & Pagos <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="cat_precios">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Listas de precio</li>
                        <li class="list-group-item">Lista precios especial</li>
                        <li class="list-group-item">Tipos de pago</li>
                        <li class="list-group-item">Bancos</li>
                        <li class="list-group-item">Cajas</li>
                    </ul>
                </div>

                <!-- Usuarios & Clientes -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#cat_usuarios">
                    Usuarios & Clientes <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="cat_usuarios">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Catálogo de usuarios</li>
                        <li class="list-group-item">Catálogo de tipos de usuario</li>
                        <li class="list-group-item">Clientes cloud</li>
                        <li class="list-group-item">Límite créditos</li>
                        <li class="list-group-item">Límite créditos especial</li>
                    </ul>
                </div>

                <!-- Geografía & Tiendas -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#cat_geografia">
                    Geografía & Tiendas <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="cat_geografia">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Estados</li>
                        <li class="list-group-item">Ciudades</li>
                        <li class="list-group-item">Plazas</li>
                        <li class="list-group-item">Tiendas</li>
                    </ul>
                </div>

                <!-- Configuración General -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#cat_config">
                    Configuración General <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="cat_config">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Catálogo de menús</li>
                        <li class="list-group-item">Catálogo de tipo de menú</li>
                        <li class="list-group-item">Catálogo de tablas</li>
                        <li class="list-group-item">Cuentas de merma</li>
                        <li class="list-group-item">Tipos de merma</li>
                        <li class="list-group-item">Subtipos de merma</li>
                    </ul>
                </div>

            </div> <!-- FIN CATÁLOGOS -->


            <!-- ======================================================= -->
            <!-- 2. INVENTARIO (Principal)                               -->
            <!-- ======================================================= -->

            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#inv_master">
                <span><i class="bi bi-box-seam me-2"></i> Inventario</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div class="collapse" id="inv_master">
                <ul class="list-group mt-2 ps-3">
                    <li class="list-group-item">Inventarios</li>
                    <li class="list-group-item">Mermas</li>
                    <li class="list-group-item">Rostizados</li>
                    <li class="list-group-item">Monedero electrónico</li>
                    <li class="list-group-item">Dinero electrónico</li>
                    <li class="list-group-item">Cancelación de tickets</li>
                    <li class="list-group-item">Cambio de lista de precio</li>
                </ul>
            </div>

            <!-- ======================================================= -->
            <!-- 3. REPORTES (Principal)                                 -->
            <!-- ======================================================= -->

            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#rep_master">
                <span><i class="bi bi-bar-chart me-2"></i> Reportes</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div class="collapse" id="rep_master">

                <!-- Ventas -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#rep_ventas">
                    Ventas <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="rep_ventas">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Concentrado de tickets</li>
                        <li class="list-group-item">Información de ventas</li>
                        <li class="list-group-item">Ventas a empleado</li>
                        <li class="list-group-item">Concentrado por tipo de precio</li>
                        <li class="list-group-item">Concentrado por ciudad y familia</li>
                        <li class="list-group-item">Concentrado por tienda y familia</li>
                        <li class="list-group-item">Concentrado por grupo y precio</li>
                    </ul>
                </div>

                <!-- Inventario -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#rep_inventario">
                    Inventario <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="rep_inventario">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Concentrado de artículos</li>
                        <li class="list-group-item">Mermas</li>
                        <li class="list-group-item">Rostizados</li>
                        <li class="list-group-item">Adeudos por empleado</li>
                    </ul>
                </div>

                <!-- Administrativos -->
                <a class="list-group-item sub-item" data-bs-toggle="collapse" href="#rep_admin">
                    Administrativos <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse ps-3" id="rep_admin">
                    <ul class="list-group mt-2">
                        <li class="list-group-item">Cortes por tienda</li>
                        <li class="list-group-item">Dinero electrónico</li>
                        <li class="list-group-item">Solicitudes de factura</li>
                        <li class="list-group-item">Pedido Oracle</li>
                    </ul>
                </div>

            </div><!-- FIN REPORTES -->


            <!-- ======================================================= -->
            <!-- 4. INTERFACES (Principal)                               -->
            <!-- ======================================================= -->

            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#inter_master">
                <span><i class="bi bi-diagram-2 me-2"></i> Interfaces</span>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div class="collapse" id="inter_master">
                <ul class="list-group mt-2 ps-3">
                    <li class="list-group-item">Interfaz de créditos</li>
                    <li class="list-group-item">Interfaz de mermas</li>
                    <li class="list-group-item">Interfaz de rosticero</li>
                    <li class="list-group-item">Interfaz Oracle</li>
                </ul>
            </div>

            <div class="mt-4">
                <button class="btn btn-sm btn-danger w-100" id="toggleLeft">
                    <i class="bi bi-chevron-left"></i> Ocultar Menú
                </button>
            </div>
        </div>
    </div>

    <!-- ────────────────────────────────────────────── -->
    <!-- 📌 MENÚ LATERAL DERECHO -->
    <!-- ────────────────────────────────────────────── -->
    <div id="sidebar-right">
        <div class="p-3">
            <h5>Menú Derecho</h5>
            <button class="btn btn-sm btn-danger" id="toggleRight">Ocultar</button>

            <hr>

            <a class="btn btn-warning w-100" data-bs-toggle="collapse" href="#collapseRight1">
                Configuración
            </a>
            <div class="collapse" id="collapseRight1">
                <ul class="list-group mt-2">
                    <li class="list-group-item">Perfil</li>
                    <li class="list-group-item">Preferencias</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ────────────────────────────────────────────── -->
    <!-- 🟩 CONTENIDO PRINCIPAL -->
    <!-- ────────────────────────────────────────────── -->
    <div id="main-content" class="p-4">
        {{-- <h2>Dashboard</h2>
        <p>Contenido principal aquí...</p> --}}

        <!-- botones para volver a mostrar los menús -->
        <button class="d-none btn btn-outline-primary" id="showLeft">Mostrar Menú Izquierdo</button>
        <button class="d-none btn btn-outline-danger float-end" id="showRight">Mostrar Menú Derecho</button>


        @yield('contenido')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>

    <script>
        const showLeft = document.getElementById('showLeft');
        const showRight = document.getElementById('showRight');
        const leftMenu = document.getElementById('sidebar-left');
        const rightMenu = document.getElementById('sidebar-right');
        const content = document.getElementById('main-content');

        // Cargar estado guardado
        function loadMenuState() {
            if (localStorage.getItem('leftCollapsed') === 'true') {
                leftMenu.classList.add('collapsed-left');
                content.classList.add('no-left');
                showLeft.classList.remove('d-none')
            }

            if (localStorage.getItem('rightCollapsed') === 'true') {
                rightMenu.classList.add('collapsed-right');
                content.classList.add('no-right');
                // showRight.classList.remove('d-none')
            }
        }

        loadMenuState();


        // Ocultar IZQUIERDO
        document.getElementById('toggleLeft').addEventListener('click', () => {
            leftMenu.classList.add('collapsed-left');
            content.classList.add('no-left');
            showLeft.classList.remove('d-none')
            localStorage.setItem('leftCollapsed', true);
        });

        // Ocultar DERECHO
        document.getElementById('toggleRight').addEventListener('click', () => {
            rightMenu.classList.add('collapsed-right');
            content.classList.add('no-right');
            showRight.classList.remove('d-none')
            localStorage.setItem('rightCollapsed', true);
        });

        // Mostrar IZQUIERDO
        document.getElementById('showLeft').addEventListener('click', () => {
            leftMenu.classList.remove('collapsed-left');
            content.classList.remove('no-left');
            showLeft.classList.add('d-none');
            localStorage.setItem('leftCollapsed', false);
        });

        // Mostrar DERECHO
        document.getElementById('showRight').addEventListener('click', () => {
            rightMenu.classList.remove('collapsed-right');
            content.classList.remove('no-right');
            showRight.classList.add('d-none');
            localStorage.setItem('rightCollapsed', false);
        });
    </script>

    @yield('scripts')

</body>

</html>
