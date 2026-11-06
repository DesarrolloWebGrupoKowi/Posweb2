<x-page-container title="Ligar Clientes por Solicitud">
    <x-card-gradient-header
        icon="link-45deg"
        title="Ligar Clientes por Solicitud"
        subtitle="Gestione las solicitudes de facturación de clientes"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <div class="cc-wrap">
                <button
                    type="button"
                    id="btnClientesCloud"
                    class="btn-header-ghost cc-btn"
                    onclick="clickClientesCloud(event)"
                    title="Descarga los clientes de Oracle Cloud al punto de venta"
                >
                    <span
                        class="cc-fill"
                        id="ccFill"
                    ></span>
                    <span
                        class="cc-content"
                        id="ccContent"
                    ><i class="bi bi-cloud-download"></i> Actualizar clientes de Cloud</span>
                    <span class="cc-bar"><span></span></span>
                </button>
                <div
                    class="cc-pop"
                    id="ccPop"
                    role="dialog"
                    aria-labelledby="ccPopTitle"
                >
                    <div
                        class="cc-pop-title"
                        id="ccPopTitle"
                    >¿Descargar clientes de Cloud?</div>
                    <div class="cc-pop-text">Tarda alrededor de un minuto. Después habrá que esperar 10 minutos para volver a hacerlo.</div>
                    <div class="cc-pop-actions">
                        <button
                            type="button"
                            class="cc-pop-cancel"
                            onclick="cerrarPopClientesCloud()"
                        >Cancelar</button>
                        <button
                            type="button"
                            class="cc-pop-ok"
                            onclick="actualizarClientesCloud()"
                        >Descargar</button>
                    </div>
                </div>
            </div>
        </x-slot:buttons>

        <x-form.form
            action="/ClientesNuevos"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? ''"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Solicitudes Pendientes
                    </h5>
                    <p class="section-content-subtitle">{{ $solicitudes->count() }} solicitudes</p>
                </div>
                <div class="d-flex gap-2">
                    <button
                        id="btnExpandir"
                        class="btn-modern btn-outline-modern"
                        onclick="toggleColumnas()"
                    >
                        <i class="bi bi-arrows-fullscreen me-2"></i>Expandir
                    </button>
                    <button
                        id="btnCopiar"
                        class="btn-modern btn-agregar"
                        onclick="copiarTabla()"
                    >
                        <i class="bi bi-clipboard me-2"></i>Copiar
                    </button>
                </div>
            </div>

            <div
                class="table-responsive"
                style="max-height: 58vh; overflow-y: auto;"
            >
                <table
                    class="table-hover table-custom table"
                    id="tablaSolicitudes"
                >
                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Id</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-person me-1"></i>Cliente</th>
                            <th><i class="bi bi-rss me-1"></i>RFC</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-person-badge me-1"></i>Tipo</th>
                            <th><i class="bi bi-geo-alt me-1"></i>Dirección</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-signpost me-1"></i>Calle</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-hash me-1"></i>Ext</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-hash me-1"></i>Int</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-mailbox me-1"></i>C.P.</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-map me-1"></i>Colonia</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-building me-1"></i>Ciudad</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-geo me-1"></i>Municipio</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-pin-map me-1"></i>Estado</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-globe me-1"></i>País</th>
                            <th><i class="bi bi-telephone me-1"></i>Teléfono</th>
                            <th><i class="bi bi-envelope me-1"></i>Correo</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-cloud me-1"></i>Email Cloud</th>
                            <th><i class="bi bi-credit-card me-1"></i>Método Pago</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-bank me-1"></i>Banco</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-credit-card-2-front me-1"></i>Cuenta</th>
                            <th
                                class="col-expandible"
                                style="display: none;"
                            ><i class="bi bi-file-text me-1"></i>Régimen Fiscal</th>
                            <th><i
                                    class="bi bi-circle-fill me-1"
                                    style="font-size: 0.5rem;"
                                ></i>Status</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solicitudes as $solicitud)
                            <tr style="text-wrap: nowrap;">
                                <td style="font-weight: 600; color: #0f172a;">{{ $solicitud->IdSolicitudFactura }}</td>
                                <td>{{ $solicitud->NomTienda }}</td>
                                <td>
                                    <span class="fw-medium">{{ $solicitud->NomCliente }}</span>
                                </td>
                                <td style="font-weight: 500;">{{ $solicitud->RFC }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->TipoPersona }}</td>
                                <td>
                                    <span
                                        class="text-truncate d-inline-block"
                                        style="max-width: 200px;"
                                        title="{{ $solicitud->Calle }} {{ $solicitud->NumExt }}"
                                    >
                                        {{ $solicitud->Calle }} {{ $solicitud->NumExt }}
                                    </span>
                                </td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Calle }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->NumExt }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->NumInt }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->CodigoPostal }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Colonia }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Ciudad }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Municipio }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Estado }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->Pais }}</td>
                                <td>{{ $solicitud->Telefono }}</td>
                                <td>
                                    <span
                                        class="text-truncate d-inline-block"
                                        style="max-width: 150px;"
                                        title="{{ $solicitud->Email }}"
                                    >
                                        {{ $solicitud->Email }}
                                    </span>
                                </td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                ></td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $solicitud->NomTipoPago }}</span>
                                </td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->NomBanco }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->NumTarjeta }}</td>
                                <td
                                    class="col-expandible"
                                    style="display: none;"
                                >{{ $solicitud->RegimenFiscal }}</td>
                                <td>
                                    @if ($solicitud->Editar)
                                        <span class="badge-status badge-pending">Actualizar</span>
                                    @else
                                        <span class="badge-status badge-active">Nuevo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <form
                                            class="d-inline-block"
                                            action="/LigarCliente"
                                        >
                                            <input
                                                type="hidden"
                                                name="idSolicitudFactura"
                                                value="{{ $solicitud->IdSolicitudFactura }}"
                                            >
                                            <button
                                                class="btn-table-action btn-table-edit"
                                                title="Ligar cliente"
                                            >
                                                <i class="bi bi-link-45deg"></i>
                                            </button>
                                        </form>
                                        <button
                                            class="btn-table-action btn-table-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalCancelarSolicitud{{ $solicitud->Id }}"
                                            title="Cancelar solicitud"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('LigarClientes.ModalCancelarSolicitud')
                        @empty
                            <tr>
                                <td colspan="24">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-inbox fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin solicitudes pendientes</h6>
                                        <small class="text-muted">No se encontraron solicitudes con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>

<script>
    let expandido = false;

    function toggleColumnas() {
        expandido = !expandido;
        const columnas = document.querySelectorAll('.col-expandible');
        const btn = document.getElementById('btnExpandir');

        columnas.forEach(col => {
            col.style.display = expandido ? '' : 'none';
        });

        if (expandido) {
            btn.innerHTML = '<i class="bi bi-arrows-collapse me-2"></i>Colapsar';
        } else {
            btn.innerHTML = '<i class="bi bi-arrows-fullscreen me-2"></i>Expandir';
        }
    }

    function copiarTabla() {
        const tabla = document.getElementById('tablaSolicitudes');
        const range = document.createRange();
        range.selectNode(tabla);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);

        try {
            document.execCommand('copy');
            // Mostrar feedback visual
            const btn = document.getElementById('btnCopiar');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Copiado!';
            btn.style.background = '#10b981';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.background = '';
            }, 2000);
        } catch (err) {
            alert('Error al copiar la tabla');
        }

        window.getSelection().removeAllRanges();
    }

    // ===== Actualizar clientes de Cloud (job XML_SQL_CUSTOMERS_IMP + Sp_Descarga_CloudTable_Customer) =====
    const CC_ESPERA_TOTAL = 600;
    const clientesCloud = {
        segundosRestantes: {{ (int) $segundosEsperaCloud }},
        enProceso: {{ $procesoCloudEnCurso ? 'true' : 'false' }},
        paso: 'job',
        ultimoEstado: @json($ultimoEstadoCloud),
        timerEspera: null,
        timerEstado: null,
    };

    function formatoTiempoCC(segundos) {
        return Math.floor(segundos / 60) + ':' + String(segundos % 60).padStart(2, '0');
    }

    function pintarBotonClientesCloud() {
        const btn = document.getElementById('btnClientesCloud');
        const contenido = document.getElementById('ccContent');
        const relleno = document.getElementById('ccFill');

        btn.classList.remove('cc-proceso', 'cc-espera');

        if (clientesCloud.enProceso) {
            btn.classList.add('cc-proceso');
            const texto = clientesCloud.paso === 'sp' ? 'Pasando al punto de venta · 2/2' : 'Descargando de Cloud · 1/2';
            contenido.innerHTML = '<span class="cc-spin"></span> ' + texto;
            relleno.style.width = '0%';
            btn.title = 'Actualización en proceso';
        } else if (clientesCloud.segundosRestantes > 0) {
            btn.classList.add('cc-espera');
            const etiqueta = clientesCloud.ultimoEstado === 'ok' ?
                '<i class="bi bi-check2"></i> Actualizado · ' :
                '<i class="bi bi-clock"></i> Disponible en ';
            contenido.innerHTML = etiqueta + formatoTiempoCC(clientesCloud.segundosRestantes);
            relleno.style.width = ((1 - clientesCloud.segundosRestantes / CC_ESPERA_TOTAL) * 100).toFixed(1) + '%';
            const disponible = new Date(Date.now() + clientesCloud.segundosRestantes * 1000);
            btn.title = 'Disponible a las ' + disponible.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
        } else {
            contenido.innerHTML = '<i class="bi bi-cloud-download"></i> Actualizar clientes de Cloud';
            relleno.style.width = '0%';
            btn.title = 'Descarga los clientes de Oracle Cloud al punto de venta';
        }
    }

    function iniciarEsperaClientesCloud(segundos) {
        clientesCloud.segundosRestantes = Math.max(0, parseInt(segundos) || 0);
        clearInterval(clientesCloud.timerEspera);
        pintarBotonClientesCloud();

        if (clientesCloud.segundosRestantes > 0) {
            clientesCloud.timerEspera = setInterval(() => {
                clientesCloud.segundosRestantes = Math.max(0, clientesCloud.segundosRestantes - 1);
                if (clientesCloud.segundosRestantes === 0) clearInterval(clientesCloud.timerEspera);
                pintarBotonClientesCloud();
            }, 1000);
        }
    }

    function clickClientesCloud(e) {
        e.stopPropagation();
        const btn = document.getElementById('btnClientesCloud');

        if (clientesCloud.enProceso) return;

        if (clientesCloud.segundosRestantes > 0) {
            btn.classList.remove('cc-shake');
            void btn.offsetWidth;
            btn.classList.add('cc-shake');
            return;
        }

        document.getElementById('ccPop').classList.toggle('show');
    }

    function cerrarPopClientesCloud() {
        document.getElementById('ccPop').classList.remove('show');
    }

    function toastClientesCloud(tipo, titulo, mensaje) {
        const contenedor = document.querySelector('.alerts-toast-container');
        if (!contenedor) return;

        const iconos = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill' };
        const toast = document.createElement('div');
        toast.className = 'toast-alert toast-' + tipo + ' show';
        toast.setAttribute('role', 'alert');
        toast.innerHTML =
            '<div class="toast-icon"><i class="bi ' + iconos[tipo] + '"></i></div>' +
            '<div class="toast-content"><strong></strong><span></span></div>' +
            '<button type="button" class="toast-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>';
        toast.querySelector('strong').textContent = titulo;
        toast.querySelector('span').textContent = mensaje;
        contenedor.appendChild(toast);

        setTimeout(() => toast.remove(), tipo === 'success' ? 5200 : 10000);
    }

    async function actualizarClientesCloud() {
        cerrarPopClientesCloud();
        if (clientesCloud.enProceso || clientesCloud.segundosRestantes > 0) return;

        clientesCloud.enProceso = true;
        clientesCloud.paso = 'job';
        pintarBotonClientesCloud();

        try {
            const resp = await fetch('/ClientesNuevos/ActualizarClientesCloud', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            });
            const data = await resp.json();

            if (data.ok || data.enProceso) {
                if (data.segundosRestantes) clientesCloud.segundosRestantes = data.segundosRestantes;
                consultarEstadoClientesCloud();
            } else {
                clientesCloud.enProceso = false;
                iniciarEsperaClientesCloud(data.segundosRestantes || 0);
                toastClientesCloud('warning', 'No se inició', data.mensaje || 'No se pudo iniciar la actualización.');
            }
        } catch (e) {
            clientesCloud.enProceso = false;
            pintarBotonClientesCloud();
            toastClientesCloud('danger', 'Sin conexión', 'No se pudo iniciar la actualización de clientes.');
        }
    }

    function consultarEstadoClientesCloud() {
        clearTimeout(clientesCloud.timerEstado);
        clientesCloud.timerEstado = setTimeout(async () => {
            try {
                const resp = await fetch('/ClientesNuevos/EstadoClientesCloud', {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await resp.json();

                if (data.estado === 'job' || data.estado === 'sp') {
                    clientesCloud.paso = data.estado;
                    pintarBotonClientesCloud();
                    consultarEstadoClientesCloud();
                    return;
                }

                clientesCloud.enProceso = false;
                clientesCloud.ultimoEstado = data.estado;
                iniciarEsperaClientesCloud(data.segundosRestantes);

                if (data.estado === 'ok') {
                    toastClientesCloud('success', 'Clientes actualizados', 'Ya puede buscar el cliente para ligarlo.');
                } else if (data.estado === 'error') {
                    toastClientesCloud('danger', 'No se actualizaron los clientes', data.mensaje);
                }
            } catch (e) {
                // Error de red momentáneo: se vuelve a intentar
                consultarEstadoClientesCloud();
            }
        }, 3000);
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.cc-wrap')) cerrarPopClientesCloud();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarPopClientesCloud();
    });

    document.addEventListener('DOMContentLoaded', () => {
        iniciarEsperaClientesCloud(clientesCloud.segundosRestantes);
        if (clientesCloud.enProceso) {
            pintarBotonClientesCloud();
            consultarEstadoClientesCloud();
        }
    });
</script>

<style>
    .cc-wrap {
        position: relative;
        display: inline-block;
    }

    .cc-btn {
        position: relative;
        overflow: hidden;
    }

    .cc-content {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    /* Relleno que avanza durante los 10 minutos de espera */
    .cc-fill {
        position: absolute;
        inset: 0 auto 0 0;
        width: 0;
        background: rgba(56, 189, 248, 0.35);
        transition: width 1s linear;
        pointer-events: none;
    }

    .cc-btn.cc-espera,
    .cc-btn.cc-proceso {
        cursor: default;
    }

    .cc-btn.cc-espera:hover,
    .cc-btn.cc-proceso:hover {
        background: var(--btn-ghost-bg);
        transform: none;
    }

    /* Barra indeterminada mientras se actualiza */
    .cc-bar {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        overflow: hidden;
        display: none;
    }

    .cc-bar span {
        position: absolute;
        top: 0;
        height: 100%;
        width: 40%;
        background: #38bdf8;
        animation: ccIndeterminada 1.4s ease-in-out infinite;
    }

    .cc-btn.cc-proceso .cc-bar {
        display: block;
    }

    .cc-spin {
        width: 13px;
        height: 13px;
        border: 2px solid rgba(255, 255, 255, 0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: ccGiro 0.8s linear infinite;
        display: inline-block;
    }

    .cc-btn.cc-shake {
        animation: ccSacudir 0.4s ease;
    }

    /* Popover de confirmación */
    .cc-pop {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        z-index: 1050;
        width: 290px;
        max-width: calc(100vw - 32px);
        background: #fff;
        color: #0f172a;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.22);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px);
        transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s;
    }

    .cc-pop.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .cc-pop::before {
        content: '';
        position: absolute;
        top: -6px;
        right: 24px;
        width: 12px;
        height: 12px;
        background: #fff;
        transform: rotate(45deg);
        border-radius: 2px;
    }

    .cc-pop-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .cc-pop-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.45;
    }

    .cc-pop-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .cc-pop-actions button {
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.8rem;
        transition: background 0.15s ease;
    }

    .cc-pop-cancel {
        background: #f1f5f9;
        color: #334155;
    }

    .cc-pop-cancel:hover {
        background: #e2e8f0;
    }

    .cc-pop-ok {
        background: #1e293b;
        color: #fff;
    }

    .cc-pop-ok:hover {
        background: #334155;
    }

    @keyframes ccIndeterminada {
        0% {
            left: -40%;
        }

        100% {
            left: 100%;
        }
    }

    @keyframes ccGiro {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes ccSacudir {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-4px);
        }

        75% {
            transform: translateX(4px);
        }
    }
</style>
