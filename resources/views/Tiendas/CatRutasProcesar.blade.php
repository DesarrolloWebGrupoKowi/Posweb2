<x-page-container title="Procesar Cortes Rutas">
    <x-card-gradient-header
        icon="scissors"
        title="Procesar Cortes Rutas"
        subtitle="Gestione el procesamiento de cortes por ruta"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Rutas Registradas
                    </h5>
                    <p class="section-content-subtitle">{{ count($sucursales) }} rutas</p>
                </div>
            </div>

            <div
                class="table-responsive"
                style="max-height: 58vh; overflow-y: auto;"
            >
                <table class="table-hover table-custom table">
                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Subinventario</th>
                            <th><i class="bi bi-shop me-1"></i>Nombre Mayoreo</th>
                            <th><i class="bi bi-person me-1"></i>Usuario</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Última Actualización</th>
                            <th class="text-center"><i class="bi bi-scissors me-1"></i>Activa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sucursales as $sucursal)
                            <tr
                                class="row-clickable"
                                data-id="{{ $sucursal->MAYOREO }}"
                                data-nombre="{{ $sucursal->DESCRIPCION_MAY }}"
                                data-subinventario="{{ $sucursal->MAYOREO }}"
                            >
                                <td style="font-weight: 600; color: #0f172a;">{{ $sucursal->MAYOREO }}</td>
                                <td>{{ $sucursal->DESCRIPCION_MAY }}</td>
                                <td
                                    class="col-usuario"
                                    data-id="{{ $sucursal->MAYOREO }}"
                                >
                                    {{ $sucursal->ceNombre }} {{ $sucursal->ceApellidos }}
                                </td>
                                <td
                                    class="col-fecha"
                                    data-id="{{ $sucursal->MAYOREO }}"
                                    style="color: #64748b;"
                                >
                                    {{ $sucursal->fechaprocesarcorte ? \Carbon\Carbon::parse($sucursal->fechaprocesarcorte)->format('d/m/Y, h:i A') : '-' }}
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input
                                            class="form-check-input-modern toggle-estado"
                                            type="checkbox"
                                            role="switch"
                                            data-id="{{ $sucursal->MAYOREO }}"
                                            {{ $sucursal->procesarcorte == 0 ? 'checked' : '' }}
                                        >
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-signpost fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin rutas registradas</h6>
                                        <small class="text-muted">No se encontraron rutas disponibles</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>

    <!-- Overlay -->
    <div
        class="side-panel-overlay"
        id="sidePanelOverlay"
    ></div>

    <!-- Panel lateral del historial -->
    <div
        class="side-panel"
        id="sidePanel"
    >
        <div
            class="d-flex align-items-center justify-content-between mb-4 pb-3"
            style="border-bottom: 1px solid #e2e8f0;"
        >
            <h5
                class="fw-bold mb-0"
                style="color: #0f172a; font-size: 1rem;"
                id="sidePanelTitle"
            >Historial de Cambios</h5>
            <button
                id="closeSidePanel"
                class="btn p-1"
                style="color: #94a3b8; font-size: 1.3rem; line-height: 1;"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div id="sidePanelContent"></div>
    </div>
</x-page-container>

<style>
    .side-panel {
        position: fixed;
        top: 0;
        right: 0;
        width: 420px;
        height: 100vh;
        background: white;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1050;
        padding: 24px;
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
        background: rgba(15, 23, 42, 0.3);
        backdrop-filter: blur(2px);
        z-index: 1040;
        display: none;
    }

    .side-panel-overlay.show {
        display: block;
    }

    .row-clickable {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .row-clickable:hover {
        background-color: #f8fafc !important;
    }

    .history-item {
        padding: 16px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        transition: all 0.2s ease;
    }

    .history-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .history-date {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .history-user {
        font-weight: 500;
        color: #475569;
        font-size: 0.85rem;
    }

    .history-old-value,
    .history-new-value {
        font-size: 0.8rem;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-block;
        font-weight: 500;
    }

    .history-old-value {
        background: #fef2f2;
        color: #dc2626;
        text-decoration: line-through;
    }

    .history-new-value {
        background: #f0fdf4;
        color: #16a34a;
    }

    .pagination-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .pagination-info {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .btn-load-more {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-load-more:hover:not(:disabled) {
        background: #e2e8f0;
    }

    .btn-load-more:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .loading-more {
        text-align: center;
        padding: 12px;
        color: #94a3b8;
        font-size: 0.8rem;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidePanel = document.getElementById('sidePanel');
        const sidePanelOverlay = document.getElementById('sidePanelOverlay');
        const closeSidePanel = document.getElementById('closeSidePanel');
        const sidePanelContent = document.getElementById('sidePanelContent');

        let currentPage = 1;
        let hasMorePages = true;
        let isLoading = false;
        let currentId = null;

        document.querySelectorAll('.row-clickable').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.form-switch') || e.target.closest('.toggle-estado'))
                    return;
                const id = this.dataset.id;
                const nombre = this.dataset.nombre;
                const subinventario = this.dataset.subinventario;
                currentPage = 1;
                hasMorePages = true;
                currentId = id;
                loadHistory(id, nombre, subinventario, true);
            });
        });

        closeSidePanel.addEventListener('click', closePanel);
        sidePanelOverlay.addEventListener('click', closePanel);

        function closePanel() {
            sidePanel.classList.remove('open');
            sidePanelOverlay.classList.remove('show');
            currentPage = 1;
            hasMorePages = true;
            currentId = null;
        }

        function setupLoadMoreButton() {
            const loadMoreBtn = document.getElementById('loadMoreHistory');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', () => {
                    if (!isLoading && hasMorePages) {
                        currentPage++;
                        const row = document.querySelector(`.row-clickable[data-id="${currentId}"]`);
                        loadHistory(currentId, row.dataset.nombre, row.dataset.subinventario, false);
                    }
                });
            }
        }

        async function loadHistory(id, nombre, subinventario, reset = true) {
            if (isLoading) return;
            isLoading = true;

            try {
                if (reset) {
                    sidePanelContent.innerHTML = `
                        <div class="text-center py-5">
                            <div class="spinner-border mb-3" style="color: #3b82f6;" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p style="color: #94a3b8; font-size: 0.85rem;">Cargando historial...</p>
                        </div>`;
                    sidePanel.classList.add('open');
                    sidePanelOverlay.classList.add('show');
                }

                const response = await fetch(`/CatRutas/historial/${id}?page=${currentPage}&per_page=10`, {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();

                document.querySelector('#sidePanelTitle').textContent = `Historial - ${subinventario}`;

                if (reset) {
                    let html = `
                        <div class="mb-4">
                            <span style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Ruta</span>
                            <p class="fw-medium mb-1" style="color: #0f172a;">${nombre}</p>
                            <span style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Subinventario</span>
                            <p class="fw-medium" style="color: #0f172a;">${subinventario}</p>
                        </div>
                        <h6 class="fw-bold mb-3" style="color: #0f172a; font-size: 0.9rem;">Cambios realizados</h6>`;

                    if (data.historial && data.historial.length > 0) {
                        html += `<div id="historyContainer">`;
                        data.historial.forEach(item => {
                            html += renderHistoryItem(item);
                        });
                        html += `</div>`;
                        html += renderPaginationControls(data);
                        sidePanelContent.innerHTML = html;
                    } else {
                        sidePanelContent.innerHTML = html + `
                            <div class="text-center py-5">
                                <div class="empty-state-icon mx-auto mb-3">
                                    <i class="bi bi-clock-history fs-3" style="color: #94a3b8;"></i>
                                </div>
                                <p style="color: #94a3b8; font-size: 0.85rem;">No hay historial de cambios disponibles</p>
                            </div>`;
                    }
                } else {
                    const historyContainer = document.querySelector('#historyContainer');
                    if (historyContainer && data.historial && data.historial.length > 0) {
                        data.historial.forEach(item => {
                            historyContainer.innerHTML += renderHistoryItem(item);
                        });
                        updatePaginationControls(data);
                    }
                }
                hasMorePages = data.hasMorePages || false;
            } catch (error) {
                console.error('Error:', error);
                if (reset) sidePanelContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-exclamation-circle fs-1 d-block mb-3" style="color: #ef4444;"></i>
                        <p style="color: #ef4444; font-size: 0.85rem;">Error al cargar el historial</p>
                    </div>`;
            } finally {
                isLoading = false;
                setupLoadMoreButton();
            }
        }

        function renderHistoryItem(item) {
            let valorAnterior = 'No definido';
            let valorNuevo = 'No definido';
            if (item.procesarcorteAnt !== null) valorAnterior = item.procesarcorteAnt == 0 ? 'Activado' :
                'Desactivado';
            if (item.procesarcorte !== undefined) valorNuevo = item.procesarcorte == 0 ? 'Activado' :
                'Desactivado';
            return `
                <div class="history-item">
                    <div class="history-date">
                        <i class="bi bi-clock me-1"></i>${formatearFecha(item.fechaprocesarcorte)}
                    </div>
                    <div class="history-user mb-2">
                        <i class="bi bi-person me-1"></i>${item.ceNombre ? item.ceNombre + ' ' + item.ceApellidos : 'Sistema'}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="history-old-value">${valorAnterior}</span>
                        <i class="bi bi-arrow-right" style="color: #94a3b8; font-size: 0.8rem;"></i>
                        <span class="history-new-value">${valorNuevo}</span>
                    </div>
                </div>`;
        }

        function renderPaginationControls(data) {
            const totalItems = data.total || data.historial.length;
            const showingItems = Math.min(currentPage * 10, totalItems);
            return `
                <div class="pagination-controls">
                    <div class="pagination-info">${showingItems} de ${totalItems} registros</div>
                    <button id="loadMoreHistory" class="btn-load-more" ${data.hasMorePages ? '' : 'disabled'}>
                        ${data.hasMorePages ? 'Cargar más' : 'No hay más'}
                    </button>
                </div>`;
        }

        function updatePaginationControls(data) {
            const totalItems = data.total || 0;
            const showingItems = Math.min(currentPage * 10, totalItems);
            const paginationInfo = document.querySelector('.pagination-info');
            const loadMoreBtn = document.getElementById('loadMoreHistory');
            if (paginationInfo) paginationInfo.textContent = `${showingItems} de ${totalItems} registros`;
            if (loadMoreBtn) {
                loadMoreBtn.disabled = !data.hasMorePages;
                loadMoreBtn.textContent = data.hasMorePages ? 'Cargar más' : 'No hay más';
            }
        }

        document.querySelectorAll(".toggle-estado").forEach(input => {
            input.addEventListener("change", async function() {
                const id = this.dataset.id;
                const nuevoEstado = this.checked ? 0 : 1;
                try {
                    const response = await fetch(`/CatRutas/procesarcorte/${id}`, {
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
                        const sucursal = data.sucursal;
                        const tdUsuario = document.querySelector(
                            `.col-usuario[data-id="${sucursal.MAYOREO}"]`);
                        if (tdUsuario && sucursal.ceNombre) tdUsuario.textContent =
                            `${sucursal.ceNombre} ${sucursal.ceApellidos}`;
                        const tdFecha = document.querySelector(
                            `.col-fecha[data-id="${sucursal.MAYOREO}"]`);
                        if (tdFecha && sucursal.fechaprocesarcorte) tdFecha.textContent =
                            formatearFecha(sucursal.fechaprocesarcorte);
                    } else {
                        this.checked = !this.checked;
                    }
                } catch (error) {
                    console.error(error);
                    this.checked = !this.checked;
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
</script>
