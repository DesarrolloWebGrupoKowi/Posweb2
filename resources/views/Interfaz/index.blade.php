<x-page-container title="Interfaz Cloud">
    <x-card-gradient-header
        icon="cloud-upload"
        title="Pedidos Rutas"
        subtitle="Consulta de pedidos por tipo de orden y fecha"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ route('interfaz.index') }}"
            id="formFiltros"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="order_type"
                    label="Tipo de Orden"
                    icon="tag"
                    col="col-md-3"
                    :options="$orderTypes->pluck('DESCRIPCION', 'ORDER_TYPE')->toArray()"
                    :value="request('order_type')"
                    placeholder="Buscar tipo de orden..."
                    autofocus
                />
                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha')"
                />
                <x-form.text
                    name="pedido"
                    label="Pedido"
                    icon="search"
                    placeholder="No. Transacción"
                    col="col-md-2"
                    :value="request('pedido')"
                />
                <x-form.checkbox-input
                    name="detallado"
                    label="Detalle"
                    :checked="request('detallado') == 'on'"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- Resultados -->
        <div class="rounded p-4 shadow-sm">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: var(--text-secondary);"
                        ></i>Pedidos Rutas
                    </h5>
                    <p class="section-content-subtitle">Listado de pedidos por tipo de orden y fecha</p>
                </div>
            </div>

            @if ($filtrosAplicados)
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5
                            class="mb-1"
                            style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                        >PEDIDOS ENCONTRADOS</h5>
                        <small style="color: var(--text-secondary); font-size: 0.85rem;">
                            {{ $pedidos->count() }} resultados
                            @if (request('pedido'))
                                | Pedido: {{ request('pedido') }}
                            @else
                                | Tipo:
                                {{ $orderTypes->firstWhere('ORDER_TYPE', request('order_type'))->DESCRIPCION ?? request('order_type') }}
                                | Fecha: {{ \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y') }}
                            @endif
                        </small>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person me-1"></i>Cliente</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                            <th><i class="bi bi-hash me-1"></i>Pedido</th>
                            <th><i class="bi bi-circle me-1"></i>Estatus</th>
                            <th><i class="bi bi-database me-1"></i>Oracle</th>
                            <th class="text-center"><i class="bi bi-box me-1"></i>Cantidad</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Venta</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalCantidad = 0;
                            $totalVenta = 0;
                        @endphp
                        @forelse ($pedidos as $pedido)
                            @php
                                $status = $pedido->STATUS ?? null;
                                $mensajeError = $pedido->MENSAJE_ERROR ?? null;
                                $transactionOn = $pedido->Transaction_On ?? null;
                                $sourceTransactionNumber = $pedido->Source_Transaction_Number ?? null;
                                $factura = $pedido->FACTURA ?? null;
                                $totalCantidad += $pedido->cantidad_total ?? 0;
                                $totalVenta += $pedido->venta_total ?? 0;
                            @endphp
                            <tr id="row-{{ $sourceTransactionNumber ?? 'temp-' . $loop->index }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            class="rounded-circle d-flex align-items-center justify-content-center icon-circle-blue flex-shrink-0">
                                            @if ($factura)
                                                <i
                                                    class="bi bi-person"
                                                    style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                ></i>
                                            @else
                                                <i
                                                    class="bi bi-box"
                                                    style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                ></i>
                                            @endif
                                        </div>
                                        <span
                                            class="text-truncate"
                                            style="max-width: 270px; display: inline-block;"
                                            title="{{ $pedido->Buying_Party_Name }}"
                                        >{{ $pedido->ORDER_TYPE }} - {{ $pedido->Buying_Party_Name }}</span>
                                    </div>
                                </td>
                                <td><span
                                        style="color: var(--text-subtle); font-size: 0.85rem;">{{ $transactionOn ? \Carbon\Carbon::parse($transactionOn)->format('d/m/Y H:i') : 'N/A' }}</span>
                                </td>
                                <td><span class="fw-semibold tags-blue">{{ $sourceTransactionNumber }}</span></td>
                                <td>
                                    <span
                                        id="status-{{ $sourceTransactionNumber }}"
                                        class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                    >
                                        @if ($status === 'PROCESADO')
                                            <i class="bi bi-check-circle me-1"></i>PROCESADO
                                        @elseif($status === 'ERROR')
                                            <i class="bi bi-x-circle me-1"></i>ERROR
                                        @else<i class="bi bi-exclamation-circle me-1"></i>SIN PROCESAR
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        id="status-oracle-{{ $sourceTransactionNumber }}"
                                        class="{{ $status === 'PROCESADO' ? 'status-oracle' : '' }} text-muted"
                                        data-pedido="{{ $sourceTransactionNumber }}"
                                    >-</span>
                                </td>
                                <td class="text-center"><span
                                        style="font-weight: 500;">{{ number_format($pedido->cantidad_total ?? 0) }}</span>
                                </td>
                                <td class="text-end"><span
                                        style="font-weight: 500;">${{ number_format($pedido->venta_total ?? 0, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @if ($status !== 'PROCESADO')
                                            <button
                                                type="button"
                                                id="btnEnviarPedido{{ $sourceTransactionNumber }}"
                                                class="btn btn-sm btn-enviar d-flex align-items-center btn-animated gap-1"
                                                style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                title="Enviar pedido a Oracle"
                                                data-pedido="{{ $sourceTransactionNumber }}"
                                                data-row-id="row-{{ $sourceTransactionNumber }}"
                                                data-original-status="{{ $status }}"
                                                data-original-mensaje="{{ $mensajeError ?? '' }}"
                                            >
                                                <i class="bi bi-send"></i> ENVIAR
                                            </button>
                                        @endif
                                        @if ($status === 'PROCESADO')
                                            <div
                                                class="acciones-oracle buttons-oracle-{{ $sourceTransactionNumber }} d-inline-block d-flex gap-2"
                                                data-pedido="{{ $sourceTransactionNumber }}"
                                            ></div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @if (!empty($mensajeError))
                                <tr
                                    id="msg-{{ $sourceTransactionNumber }}"
                                    class="bg-light"
                                >
                                    <td
                                        colspan="8"
                                        class="py-2 ps-5"
                                    >
                                        <small
                                            id="mensaje-container-{{ $sourceTransactionNumber }}"
                                            class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}"
                                        >
                                            <strong id="mensaje-titulo-{{ $sourceTransactionNumber }}">
                                                @if ($transactionOn)
                                                    {{ \Carbon\Carbon::parse($transactionOn)->format('d/m/Y H:i') }} -
                                                @endif
                                                {{ $status === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                            </strong>
                                            <span
                                                id="mensaje-texto-{{ $sourceTransactionNumber }}">{{ $mensajeError }}</span>
                                        </small>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="py-5 text-center"
                                >
                                    @if (!$filtrosAplicados)
                                        <i
                                            class="bi bi-funnel"
                                            style="font-size: 2.5rem; color: var(--text-muted);"
                                        ></i>
                                        <h6 style="color: var(--text-primary);">Aplique filtros para consultar</h6>
                                        <p class="text-muted">Busque por número de pedido o seleccione tipo de orden y
                                            fecha</p>
                                    @else
                                        <i
                                            class="bi bi-inbox"
                                            style="font-size: 2.5rem; color: var(--text-muted);"
                                        ></i>
                                        <h5 style="color: var(--text-primary);">Sin resultados</h5>
                                        <p class="text-muted">No se encontraron pedidos con los filtros seleccionados
                                        </p>
                                        <a
                                            href="{{ route('interfaz.index') }}"
                                            class="btn btn-sm"
                                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px;"
                                        >
                                            <i class="bi bi-x-circle me-2"></i>Limpiar filtros
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($pedidos->count() > 0)
                        <tfoot>
                            <tr class="bg-table-totals">
                                <td
                                    colspan="5"
                                    style="color: var(--text-primary);"
                                >Totales:</td>
                                <td
                                    class="text-center"
                                    style="font-weight: 700;"
                                >{{ number_format($totalCantidad) }}</td>
                                <td
                                    class="text-end"
                                    style="color: var(--success-color); font-weight: 700;"
                                >${{ number_format($totalVenta, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>

    <script>
        function fetchStatusOracle(item) {
            const pedido = item.dataset.pedido;
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;
            fetch(apiUrl).then(response => response.json()).then(data => {
                if (data.ok && data.dato?.lines) {
                    const estatusLineas = data.dato.lines.map(line => line.status);
                    const estatusUnicos = [...new Set(estatusLineas)];
                    if (estatusUnicos.length > 1) {
                        item.innerHTML = `<span class="tags-green">${estatusUnicos.join(', ')}</span>`;
                        if (estatusUnicos.includes('Closed')) fetchBuscarUUID(pedido, item);
                    }
                    if (estatusUnicos.length == 1) {
                        item.innerHTML = `<span class="tags-green">${estatusUnicos[0]}</span>`;
                        let estatusPedido = estatusUnicos[0];
                        const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                        if (estatusPedido == 'Awaiting Shipping' && contenedor) {
                            contenedor.innerHTML = '';
                            botonDespachoInventario(contenedor, pedido);
                        }
                        if (estatusPedido == 'Awaiting Billing' && contenedor) {
                            contenedor.innerHTML = '';
                            botonGenerarFactura(contenedor, pedido);
                        }
                        if (estatusPedido == 'Closed') fetchBuscarUUID(pedido, item);
                    }
                } else {
                    item.innerHTML = '<span class="tags-red">Sin datos</span>';
                }
            }).catch(() => {
                item.innerHTML = '<span class="text-muted">-</span>';
            });
        }

        function actualizarFilasOracle() {
            document.querySelectorAll('.status-oracle').forEach(item => fetchStatusOracle(item));
        }

        function getStatusLoop(pedido, estatusSiguiente) {
            const item = document.getElementById(`status-oracle-${pedido}`);
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;
            fetch(apiUrl).then(response => response.json()).then(data => {
                if (data.ok && data.dato?.lines) {
                    const estatusLineas = data.dato.lines.map(line => line.status);
                    const estatusUnicos = [...new Set(estatusLineas)];
                    if (estatusUnicos.length == 1) {
                        let estatusPedido = estatusUnicos[0];
                        item.innerHTML = `<span class="tags-green">${estatusPedido}</span>`;
                        const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                        if (estatusPedido == 'Awaiting Billing' && estatusSiguiente == 'Awaiting Billing' &&
                            contenedor) {
                            contenedor.innerHTML = '';
                            botonGenerarFactura(contenedor, pedido);
                        } else if (estatusPedido == 'Closed' && estatusSiguiente == 'Closed') fetchBuscarUUID(
                            pedido, item);
                        else setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                    } else setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                } else setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
            }).catch(() => setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000));
        }

        function botonDespachoInventario(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.className = 'btn-amber btn-sm';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> DESPACHO';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Despachando...';
                btn.disabled = true;
                fetch(`https://oracledespachorest.kowi.com.mx/api/PickWave/Despacho?Orden=${pedido}`).then(r => r
                    .json()).then(data => {
                    if (data.ok) {
                        getStatusLoop(pedido, 'Awaiting Billing');
                        btn.remove();
                    } else {
                        btn.innerHTML = '❌ Error';
                        btn.disabled = false;
                    }
                }).catch(() => {
                    btn.innerHTML = '❌ Error';
                    btn.disabled = false;
                });
            });
            contenedor.appendChild(btn);
        }

        function botonGenerarFactura(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.className = 'btn-blue btn-sm';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> GENERAR FACTURA';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generando...';
                btn.disabled = true;
                fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/Factura?Orden=${pedido}`, {
                    method: 'POST'
                }).then(r => r.json()).then(data => {
                    if (data.ok) {
                        getStatusLoop(pedido, 'Closed');
                        btn.remove();
                    } else {
                        btn.innerHTML = '❌ Error';
                        btn.disabled = false;
                    }
                }).catch(() => {
                    btn.innerHTML = '❌ Error';
                    btn.disabled = false;
                });
            });
            contenedor.appendChild(btn);
        }

        function fetchBuscarUUID(pedido, item) {
            fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/FacturaOracle?Orden=${pedido}`).then(response =>
                response.json()).then(data => {
                if (data.ok) {
                    const uuidApex = data.dato?.uUidApex;
                    const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                    if (uuidApex && uuidApex.trim() !== '') {
                        item.innerHTML = `<span class="tags-green">${item.textContent += ` & Timbrado`}</span>`;
                        if (contenedor) {
                            contenedor.innerHTML = '';
                            botonesPDFXML(contenedor, pedido);
                        }
                        mostrarUUIDApex(pedido, uuidApex);
                    } else {
                        item.innerHTML = `<span class="tags-green">Closed</span>`;
                        mostrarSinTimbrar(pedido);
                    }
                }
            }).catch(() => console.error('Error al buscar UUID en Oracle'));
        }

        function mostrarUUIDApex(pedido, uuidApex) {
            let msgRow = document.getElementById('msg-' + pedido);
            const mainRow = document.getElementById('row-' + pedido);
            if (!msgRow && mainRow) {
                msgRow = document.createElement('tr');
                msgRow.id = 'msg-' + pedido;
                msgRow.className = 'bg-light';
                msgRow.innerHTML =
                    `<td colspan="8" class="py-2 ps-5"><small id="mensaje-container-${pedido}" class="text-success"><span id="uuid-info-${pedido}"></span></small></td>`;
                mainRow.after(msgRow);
            }
            const uuidInfoSpan = document.getElementById('uuid-info-' + pedido);
            if (uuidInfoSpan) uuidInfoSpan.innerHTML = `<strong>UUID Apex:</strong> <span>${uuidApex}</span>`;
            const mensajeContainer = document.getElementById('mensaje-container-' + pedido);
            if (mensajeContainer) mensajeContainer.className = 'text-success';
        }

        function mostrarSinTimbrar(pedido) {
            let msgRow = document.getElementById('msg-' + pedido);
            const mainRow = document.getElementById('row-' + pedido);
            if (!msgRow && mainRow) {
                msgRow = document.createElement('tr');
                msgRow.id = 'msg-' + pedido;
                msgRow.className = 'bg-light';
                msgRow.innerHTML =
                    `<td colspan="8" class="py-2 ps-5"><small id="mensaje-container-${pedido}" class="text-success"><span id="uuid-info-${pedido}"></span></small></td>`;
                mainRow.after(msgRow);
            }
            const uuidInfoSpan = document.getElementById('uuid-info-' + pedido);
            if (uuidInfoSpan) uuidInfoSpan.innerHTML =
                `<i class="bi bi-info-circle me-1"></i>Pedido cerrado, pendiente de timbrado`;
            const mensajeContainer = document.getElementById('mensaje-container-' + pedido);
            if (mensajeContainer) mensajeContainer.className = 'text-success';
        }

        function botonesPDFXML(contenedor, pedido) {
            const btnPDF = document.createElement('a');
            btnPDF.className = 'btn-blue btn-sm';
            btnPDF.innerHTML = '<i class="bi bi-download"></i> PDF';
            btnPDF.href = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${pedido}`;
            btnPDF.target = '_blank';
            const btnXML = document.createElement('a');
            btnXML.className = 'btn-gray btn-sm';
            btnXML.innerHTML = '<i class="bi bi-download"></i> XML';
            btnXML.href = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden=${pedido}`;
            btnXML.target = '_blank';
            contenedor.appendChild(btnPDF);
            contenedor.appendChild(btnXML);
        }

        document.addEventListener('DOMContentLoaded', () => actualizarFilasOracle());

        document.querySelectorAll('.btn-enviar').forEach(button => {
            button.addEventListener('click', async function() {
                const pedidoId = this.getAttribute('data-pedido');
                const rowId = this.getAttribute('data-row-id');
                const btn = this;
                const row = document.getElementById(rowId);
                btn.disabled = true;
                const origHTML = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                try {
                    const response = await fetch(`/DashTienda/enviar-pedido/${pedidoId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });
                    const result = await response.json();
                    const cells = row.querySelectorAll('td');
                    const statusCell = cells[3];
                    const oracleCell = cells[4];
                    const accionesCell = cells[7];
                    const msgRow = document.getElementById('msg-' + pedidoId);
                    if (result.ok) {
                        statusCell.innerHTML =
                            `<span id="status-${pedidoId}" class="tags-green"><i class="bi bi-check-circle me-1"></i>PROCESADO</span>`;
                        oracleCell.innerHTML =
                            `<span id="status-oracle-${pedidoId}" class="status-oracle text-muted" data-pedido="${pedidoId}">-</span>`;
                        setTimeout(() => {
                            const oracleItem = document.getElementById(
                                `status-oracle-${pedidoId}`);
                            if (oracleItem) fetchStatusOracle(oracleItem);
                        }, 2000);
                        if (result.message) {
                            const mensajeHTML =
                                `<strong id="mensaje-titulo-${pedidoId}">Mensaje:</strong><span id="mensaje-texto-${pedidoId}">${result.message}</span>`;
                            if (msgRow) {
                                msgRow.querySelector('td small').innerHTML = mensajeHTML;
                                msgRow.querySelector('td small').className = 'text-success';
                                msgRow.className = 'bg-light';
                            } else {
                                const newMsgRow = document.createElement('tr');
                                newMsgRow.id = 'msg-' + pedidoId;
                                newMsgRow.className = 'bg-light';
                                newMsgRow.innerHTML =
                                    `<td colspan="8" class="py-2 ps-5"><small id="mensaje-container-${pedidoId}" class="text-success">${mensajeHTML}</small></td>`;
                                row.after(newMsgRow);
                            }
                        }
                        accionesCell.innerHTML =
                            `<div class="d-flex align-items-center justify-content-center gap-1"><div class="acciones-oracle buttons-oracle-${pedidoId} d-inline-block d-flex gap-2" data-pedido="${pedidoId}"></div></div>`;
                    } else {
                        statusCell.innerHTML =
                            `<span id="status-${pedidoId}" class="tags-red"><i class="bi bi-x-circle me-1"></i>ERROR</span>`;
                        const mensajeHTML =
                            `<strong id="mensaje-titulo-${pedidoId}">Error:</strong><span id="mensaje-texto-${pedidoId}">${result.message}</span>`;
                        if (msgRow) {
                            msgRow.querySelector('td small').innerHTML = mensajeHTML;
                            msgRow.querySelector('td small').className = 'text-danger';
                            msgRow.className = 'bg-light';
                        } else {
                            const newMsgRow = document.createElement('tr');
                            newMsgRow.id = 'msg-' + pedidoId;
                            newMsgRow.className = 'bg-light';
                            newMsgRow.innerHTML =
                                `<td colspan="8" class="py-2 ps-5"><small id="mensaje-container-${pedidoId}" class="text-danger">${mensajeHTML}</small></td>`;
                            row.after(newMsgRow);
                        }
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                    }
                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                    console.error('Error de conexión:', error);
                }
            });
        });
    </script>
</x-page-container>
