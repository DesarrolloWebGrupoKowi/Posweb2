@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Procesar Cortes Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <style>
        /* Estilos del switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 22px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            transition: .4s;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #0d6efd;
        }

        input:checked+.slider:before {
            transform: translateX(23px);
        }

        /* Estilos del panel lateral */
        .side-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 1050;
            padding: 20px;
            overflow-y: auto;
        }

        .side-panel.open {
            transform: translateX(0);
        }

        .side-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .side-panel-overlay.show {
            display: block;
        }

        .side-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .side-panel-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .side-panel-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .history-item {
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .history-date {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 5px;
        }

        .history-user {
            font-weight: 500;
            color: #333;
        }

        .history-old-value,
        .history-new-value {
            font-size: 0.875rem;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin: 2px;
        }

        .history-old-value {
            background: #ffe6e6;
            color: #d63031;
            text-decoration: line-through;
        }

        .history-new-value {
            background: #e6ffe6;
            color: #27ae60;
        }

        .row-clickable {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .row-clickable:hover {
            background-color: #f5f5f5 !important;
        }

        .history-empty {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .no-history {
            font-style: italic;
            color: #999;
        }

        .pagination-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #666;
        }

        .btn-load-more {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }

        .btn-load-more:hover:not(:disabled) {
            background-color: #0b5ed7;
        }

        .btn-load-more:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .loading-more {
            text-align: center;
            padding: 10px;
            color: #666;
        }
    </style>

    <!-- Overlay para cerrar panel -->
    <div
        class="side-panel-overlay"
        id="sidePanelOverlay"
    ></div>

    <!-- Panel lateral del historial -->
    <div
        class="side-panel"
        id="sidePanel"
    >
        <div class="side-panel-header">
            <h3
                class="side-panel-title"
                id="sidePanelTitle"
            >Historial de Cambios</h3>
            <button
                class="side-panel-close"
                id="closeSidePanel"
            >&times;</button>
        </div>

        <div id="sidePanelContent">
            <!-- El contenido se cargará dinámicamente -->
        </div>
    </div>

    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <div
            class="card border-0 p-4"
            style="border-radius: 10px"
        >
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Procesar Cortes Tiendas'])
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <div
            class="content-table content-table-full card border-0 p-4"
            style="border-radius: 10px"
        >
            <form
                class="d-flex align-items-center justify-content-end flex-wrap gap-2 pb-2"
                action="/CatTiendasProcesar"
                method="get"
            >
                <div
                    class="input-group"
                    style="max-width: 300px"
                >
                    <input
                        type="text"
                        class="form-control rounded"
                        style="line-height: 18px"
                        name="filtroTienda"
                        id="filtroTienda"
                        placeholder="Buscar tienda..."
                        value="{{ request()->get('filtroTienda', '') }}"
                        autofocus
                    >
                </div>
                <div>
                    <select
                        name="filtroStatus"
                        id="filtroStatus"
                        class="form-select rounded"
                        style="line-height: 18px"
                        style="min-width: 120px;"
                    >
                        <option value="">Estatus</option>
                        <option
                            value="0"
                            {{ request()->get('filtroStatus', '') === '0' ? 'selected' : '' }}
                        >Activa</option>
                        <option
                            value="1"
                            {{ request()->get('filtroStatus', '') === '1' ? 'selected' : '' }}
                        >Inactiva</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tienda</th>
                        <th>Ciudad</th>
                        <th>Usuario</th>
                        <th>Ultima Actualización</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Procesar Cortes</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tiendas) <= 0)
                        <tr>
                            <td colspan="6">No Hay Tiendas!</td>
                        </tr>
                    @else
                        @foreach ($tiendas as $tienda)
                            <tr
                                class="row-clickable"
                                data-id="{{ $tienda->IdTienda }}"
                                data-nombre="{{ $tienda->NomTienda }}"
                                data-subinventario="{{ $tienda->IdTienda }}"
                            >
                                <td>{{ $tienda->IdTienda }}</td>
                                <td>{{ $tienda->NomTienda }}</td>
                                <td>{{ $tienda->ccNomCiudad }}</td>
                                <td
                                    class="col-usuario"
                                    data-id="{{ $tienda->IdTienda }}"
                                >
                                    @if ($tienda->ceNombre)
                                        {{ $tienda->ceNombre }} {{ $tienda->ceApellidos }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td
                                    class="col-fecha"
                                    data-id="{{ $tienda->IdTienda }}"
                                >
                                    @if ($tienda->fechaprocesarcorte)
                                        {{ \Carbon\Carbon::parse($tienda->fechaprocesarcorte)->format('d/m/Y, h:i A') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($tienda->Status == 0)
                                        <span
                                            class="tags-green"
                                            title="Activa"
                                        > Activa </span>
                                    @else
                                        <span
                                            class="tags-red"
                                            title="Inactiva"
                                        > Inactiva </span>
                                    @endif
                                </td>
                                <td>
                                    <label class="switch">
                                        <input
                                            type="checkbox"
                                            class="toggle-estado"
                                            data-id="{{ $tienda->IdTienda }}"
                                            {{ $tienda->procesarcorte == 0 ? 'checked' : '' }}
                                            {{ $tienda->Status == 1 ? 'disabled' : '' }}
                                        >
                                        <span
                                            class="slider round"
                                            style="{{ $tienda->Status == 1 ? 'opacity: 0.3;' : '' }}"
                                        ></span>
                                    </label>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidePanel = document.getElementById('sidePanel');
            const sidePanelOverlay = document.getElementById('sidePanelOverlay');
            const closeSidePanel = document.getElementById('closeSidePanel');
            const sidePanelContent = document.getElementById('sidePanelContent');

            // Variables para el estado de paginación
            let currentPage = 1;
            let hasMorePages = true;
            let isLoading = false;
            let currentId = null;

            // Eventos para abrir/cerrar panel
            document.querySelectorAll('.row-clickable').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Evitar que se active al hacer clic en el switch
                    if (e.target.closest('.switch') || e.target.closest('.toggle-estado')) {
                        return;
                    }

                    const id = this.dataset.id;
                    const nombre = this.dataset.nombre;
                    const subinventario = this.dataset.subinventario;

                    // Reiniciar paginación para nuevo centro
                    currentPage = 1;
                    hasMorePages = true;
                    currentId = id;

                    loadHistory(id, nombre, subinventario, true);
                });
            });

            closeSidePanel.addEventListener('click', closePanel);
            sidePanelOverlay.addEventListener('click', closePanel);

            // Función para cerrar panel
            function closePanel() {
                sidePanel.classList.remove('open');
                sidePanelOverlay.classList.remove('show');
                currentPage = 1;
                hasMorePages = true;
                currentId = null;
            }

            // Función para cargar más historial
            function setupLoadMoreButton() {
                const loadMoreBtn = document.getElementById('loadMoreHistory');
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', () => {
                        if (!isLoading && hasMorePages) {
                            currentPage++;
                            const row = document.querySelector(`.row-clickable[data-id="${currentId}"]`);
                            loadHistory(
                                currentId,
                                row.dataset.nombre,
                                row.dataset.subinventario,
                                false
                            );
                        }
                    });
                }
            }

            // Función para cargar historial
            async function loadHistory(id, nombre, subinventario, reset = true) {
                if (isLoading) return;

                isLoading = true;

                try {
                    if (reset) {
                        // Mostrar panel con estado de carga inicial
                        sidePanelContent.innerHTML = `
                    <div class="history-empty">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3">Cargando historial...</p>
                    </div>
                `;

                        sidePanel.classList.add('open');
                        sidePanelOverlay.classList.add('show');
                    } else {
                        // Mostrar indicador de carga adicional
                        const loadMoreBtn = document.getElementById('loadMoreHistory');
                        if (loadMoreBtn) loadMoreBtn.disabled = true;

                        const loadingDiv = document.createElement('div');
                        loadingDiv.className = 'loading-more';
                        loadingDiv.innerHTML = `
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <span class="ms-2">Cargando más registros...</span>
                `;

                        const paginationControls = document.querySelector('.pagination-controls');
                        if (paginationControls) {
                            paginationControls.parentNode.insertBefore(loadingDiv, paginationControls);
                        }
                    }

                    // Obtener historial del servidor con paginación
                    const response = await fetch(
                        `/CatTiendas/historial/${id}?page=${currentPage}&per_page=10`, {
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                    const data = await response.json();

                    // Actualizar título del panel (CORREGIDO)
                    document.querySelector('#sidePanelTitle').textContent = `Historial - ${subinventario}`;

                    if (reset) {
                        // Renderizar desde el inicio
                        let html = `<p class="mb-3"><strong>${nombre}</strong></p>`;
                        html += `<p class="mb-4">Subinventario: <strong>${subinventario}</strong></p>`;
                        html += `<h6 class="mb-3">Cambios realizados:</h6>`;

                        if (data.historial && data.historial.length > 0) {
                            html += `<div id="historyContainer">`;
                            data.historial.forEach(item => {
                                html += renderHistoryItem(item);
                            });
                            html += `</div>`;

                            // Agregar controles de paginación
                            html += renderPaginationControls(data);

                            sidePanelContent.innerHTML = html;
                        } else {
                            sidePanelContent.innerHTML = `
                        <p class="mb-3"><strong>${nombre}</strong></p>
                        <p class="mb-4">Subinventario: <strong>${subinventario}</strong></p>
                        <div class="history-empty">
                            <p class="no-history">No hay historial de cambios disponibles</p>
                        </div>
                    `;
                        }
                    } else {
                        // Agregar nuevos registros
                        const historyContainer = document.querySelector('#historyContainer');
                        if (historyContainer && data.historial && data.historial.length > 0) {
                            data.historial.forEach(item => {
                                historyContainer.innerHTML += renderHistoryItem(item);
                            });

                            // Actualizar controles de paginación
                            updatePaginationControls(data);

                            // Remover indicador de carga
                            const loadingDiv = document.querySelector('.loading-more');
                            if (loadingDiv) loadingDiv.remove();
                        }
                    }

                    hasMorePages = data.hasMorePages || false;

                } catch (error) {
                    console.error('Error al cargar historial:', error);

                    if (reset) {
                        sidePanelContent.innerHTML = `
                    <div class="history-empty">
                        <p class="text-danger">Error al cargar el historial</p>
                    </div>
                `;
                    }
                } finally {
                    isLoading = false;

                    if (!reset) {
                        const loadMoreBtn = document.getElementById('loadMoreHistory');
                        if (loadMoreBtn) {
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.textContent = hasMorePages ? 'Cargar más' : 'No hay más registros';
                        }

                        const loadingDiv = document.querySelector('.loading-more');
                        if (loadingDiv) loadingDiv.remove();
                    }

                    // Configurar botón de cargar más
                    setupLoadMoreButton();
                }
            }

            function renderHistoryItem(item) {
                // Determinar los valores antiguo y nuevo basado en procesarcorte
                // Asumo que en tu historial guardas el cambio de estado
                let valorAnterior = 'No definido';
                let valorNuevo = 'No definido';

                if (item.procesarcorteAnt !== null) {
                    valorAnterior = item.procesarcorteAnt == 0 ? 'Activado' : 'Desactivado';
                }
                if (item.procesarcorte !== undefined) {
                    valorNuevo = item.procesarcorte == 0 ? 'Activado' : 'Desactivado';
                }
                // valorNuevo = item.procesarcorte == 0 ? 'Activado' : 'Desactivado';

                return `
                    <div class="history-item">
                        <div class="history-date">
                            ${formatearFecha(item.fechaprocesarcorte)}
                        </div>
                        <div class="history-user mb-2">
                            Usuario: ${item.ceNombre ? item.ceNombre + ' ' + item.ceApellidos : 'Sistema'}
                        </div>
                        <div>
                            <span class="history-old-value">${valorAnterior}</span>
                            <span>→</span>
                            <span class="history-new-value">${valorNuevo}</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">ID: ${item.id || item.IdHistorialProcesarCorte || 'N/A'}</small>
                        </div>
                    </div>
                `;
            }

            // Función para renderizar controles de paginación
            function renderPaginationControls(data) {
                const totalItems = data.total || data.historial.length;
                const showingItems = Math.min(currentPage * 10, totalItems);

                return `
                    <div id="historyContainer"></div>
                    <div class="pagination-controls">
                        <div class="pagination-info">
                            Mostrando ${showingItems} de ${totalItems} registros
                        </div>
                        <button
                            id="loadMoreHistory"
                            class="btn-load-more"
                            ${data.hasMorePages ? '' : 'disabled'}
                        >
                            ${data.hasMorePages ? 'Cargar más' : 'No hay más registros'}
                        </button>
                    </div>
                `;
            }

            // Función para actualizar controles de paginación
            function updatePaginationControls(data) {
                const totalItems = data.total || 0;
                const showingItems = Math.min(currentPage * 10, totalItems);
                const paginationInfo = document.querySelector('.pagination-info');
                const loadMoreBtn = document.getElementById('loadMoreHistory');

                if (paginationInfo) {
                    paginationInfo.textContent = `Mostrando ${showingItems} de ${totalItems} registros`;
                }

                if (loadMoreBtn) {
                    loadMoreBtn.disabled = !data.hasMorePages;
                    loadMoreBtn.textContent = data.hasMorePages ? 'Cargar más' : 'No hay más registros';
                }
            }

            document.querySelectorAll(".toggle-estado").forEach(input => {
                input.addEventListener("change", async function() {

                    const id = this.dataset.id;
                    const nuevoEstado = this.checked ? 0 : 1;

                    try {
                        const response = await fetch(`/CatTiendas/procesarcorte/${id}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                procesarcorte: nuevoEstado
                            })
                        });

                        const data = await response.json();

                        if (data.ok) {
                            mostrarAlertaMini("Estado actualizado");

                            const tienda = data.tienda;
                            // Usuario
                            const tdUsuario = document.querySelector(
                                `.col-usuario[data-id="${tienda.IdTienda}"]`
                            );

                            if (tdUsuario && tienda.ceNombre) {
                                tdUsuario.textContent =
                                    `${tienda.ceNombre} ${tienda.ceApellidos}`;
                            }

                            // Fecha
                            const tdFecha = document.querySelector(
                                `.col-fecha[data-id="${tienda.IdTienda}"]`
                            );

                            if (tdFecha && tienda.fechaprocesarcorte) {
                                tdFecha.textContent = formatearFecha(tienda.fechaprocesarcorte);
                            }

                        } else {
                            alert("Hubo un error guardando el estado");
                            this.checked = !this.checked;
                        }


                    } catch (error) {
                        console.error(error);
                        alert("Error de comunicación con el servidor");
                        this.checked = !this.checked; // Revertir
                    }
                });
            });

            function formatearFecha(fecha) {
                const d = new Date(fecha.replace(' ', 'T'));

                return d.toLocaleString('es-MX', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }).toUpperCase();
            }
        });

        function mostrarAlertaMini(mensaje = "Cambios guardados") {
            const alerta = document.getElementById("alert-mini");
            const texto = document.getElementById("alert-mini-text");

            texto.textContent = mensaje;

            alerta.classList.remove("d-none");
            alerta.classList.add("show");

            // Ocultar después de 2 segundos
            setTimeout(() => {
                alerta.classList.add("d-none");
                alerta.classList.remove("show");
            }, 2000);
        }
    </script>

@endsection
