<x-page-container title="Ligar Clientes por Solicitud">
    <x-card-gradient-header
        icon="link-45deg"
        title="Ligar Clientes por Solicitud"
        subtitle="Gestione las solicitudes de facturación de clientes"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
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
</script>
