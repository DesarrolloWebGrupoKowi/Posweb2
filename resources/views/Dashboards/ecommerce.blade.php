<x-page-container title="Ventas Ecommerce">
    <x-card-gradient-header
        icon="cart"
        title="Ventas Ecommerce"
        subtitle="Ventas entregadas de la tienda en línea por mes"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="{{ route('DashEcommerce') }}">
            <x-form.group>
                <x-form.select
                    name="centro_venta"
                    label="Centro de venta"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todos los centros"
                    :options="$centrosVenta"
                />
                <div class="col-md-2">
                    <label
                        class="form-label"
                        for="mes"
                    >
                        <i class="bi bi-calendar3 me-1"></i>
                        Mes
                    </label>
                    <input
                        type="month"
                        class="form-control form-input"
                        name="mes"
                        id="mes"
                        value="{{ $mes }}"
                        autofocus
                    >
                </div>
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

        {{-- SECCIÓN 2: KPIs --}}
        <div class="p-4">
            <div class="row g-3">
                {{-- KPI 1: Solic. Facturas --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-purple-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-purple-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Solic. Facturas</span>
                                <i
                                    class="bi bi-file-text"
                                    style="color: var(--kpi-icon-purple); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['solicitudes_factura'] }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">
                                de {{ $kpis['tickets'] }} ventas</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 2: Tickets --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-green-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-green-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-green-text-icon); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Tickets</span>
                                <i
                                    class="bi bi-receipt"
                                    style="color: var(--kpi-icon-green); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['tickets'] }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">Prom:
                                ${{ number_format($kpis['promedio_ticket'], 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 3: Kilos --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-orange-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-orange-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Kilos</span>
                                <i
                                    class="bi bi-box"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ number_format($kpis['kilos'], 1) }} kg</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">
                                {{ number_format($kpis['kilos_por_ticket'], 2) }} kg/ticket</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 4: Ventas del mes --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-indigo-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-indigo-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-indigo-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Ventas del mes</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: var(--kpi-icon-indigo); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($kpis['ventas_mes'], 2) }}</h3>
                            <div class="d-flex align-items-center gap-1">
                                <span
                                    style="color: {{ $kpis['ventas_vs_mes_anterior'] >= 0 ? '#10b981' : '#ef4444' }}; font-size: 0.78rem; font-weight: 600;"
                                >
                                    <i
                                        class="bi bi-arrow-{{ $kpis['ventas_vs_mes_anterior'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($kpis['ventas_vs_mes_anterior']) }}%
                                </span>
                                <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">vs mes anterior</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: TABLA --}}
        <div class="px-4 pb-4">
            <div class="card-chart rounded p-4 shadow-sm">
                <div
                    class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                    <div>
                        <h5
                            class="mb-1"
                            style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                        >VENTAS ECOMMERCE {{ $centrosVenta[request('centro_venta')] ?? '' }}</h5>
                        <small
                            style="color: var(--text-secondary); font-size: 0.85rem;">{{ ucfirst($inicio->locale('es')->isoFormat('MMMM [de] YYYY')) }}</small>
                    </div>
                    @if ($puedeProcesar)
                        <x-dashboard-ecommerce-procesar :diasPorProcesar="$diasPorProcesar" />
                    @endif
                </div>

                @php
                    $allowedUserTypes = [1, 4, 9, 11];
                    $puedeAccionar = in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes);
                    $columnas = $puedeAccionar ? 10 : 9;
                @endphp

                <div class="table-responsive">
                    <table class="table-hover table-custom table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-person me-1"></i>Cliente</th>
                                <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                <th><i class="bi bi-circle me-1"></i>Estatus</th>
                                <th><i class="bi bi-database me-1"></i>Oracle</th>
                                <th><i class="bi bi-shop me-1"></i>Centro de venta</th>
                                <th class="text-end"><i class="bi bi-box me-1"></i>Cantidad</th>
                                <th class="text-end"><i class="bi bi-truck me-1"></i>Envío</th>
                                <th class="text-end"><i class="bi bi-cash me-1"></i>Importe</th>
                                @if ($puedeAccionar)
                                    <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                @php
                                    $oracleData = $pedido->OracleData;
                                    $sourceIdentifier = $oracleData->Source_Transaction_Identifier ?? null;
                                    $sourceTransactionNumber = $oracleData->Source_Transaction_Number ?? null;
                                    $status = $oracleData->STATUS ?? null;
                                @endphp
                                <tr @if ($sourceIdentifier) id="row-{{ $sourceIdentifier }}" @endif>
                                    {{-- Icono como en el corte de tienda: caja sin factura, persona con solicitud de factura --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded-circle d-flex align-items-center justify-content-center icon-circle-blue flex-shrink-0">
                                                <i
                                                    class="bi bi-{{ $pedido->SolicitudFactura ? 'person' : 'box' }}"
                                                    style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                ></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="text-truncate"
                                                    style="max-width: 300px; display: inline-block;"
                                                    title="{{ $pedido->Cliente }}"
                                                >{{ $pedido->Cliente }}</span>
                                                @if ($pedido->SolicitudFactura)
                                                    <small style="color: var(--text-secondary);">
                                                        {{ $pedido->ClienteEcommerce }} | {{ $pedido->NomTipoPago }}
                                                        @if ($pedido->ConstanciaFiscal)
                                                            <a
                                                                href="{{ $pedido->ConstanciaFiscal }}"
                                                                target="_blank"
                                                                title="Constancia de situación fiscal"
                                                            ><i class="bi bi-download"></i> Constancia</a>
                                                        @endif
                                                    </small>
                                                @endif
                                                @if ($pedido->FaltanDatosFactura)
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-triangle"></i> Faltan datos del cliente para generar el pedido
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-wrap: nowrap">
                                        {{ \Carbon\Carbon::parse($pedido->FechaPago)->format('d/m/Y') }}</td>
                                    <td>
                                        <x-dashboard-ecommerce-oracle
                                            :oracleData="$oracleData"
                                            mostrar="pedido"
                                        />
                                    </td>
                                    <td>
                                        <x-dashboard-ecommerce-oracle
                                            :oracleData="$oracleData"
                                            :statusId="$sourceIdentifier ? 'status-' . $sourceIdentifier : null"
                                            mostrar="estatus"
                                        />
                                    </td>
                                    <td class="text-center">
                                        @if ($status === 'PROCESADO' && $sourceTransactionNumber)
                                            <span
                                                id="status-oracle-{{ $sourceTransactionNumber }}"
                                                class="status-oracle text-muted"
                                                data-pedido="{{ $sourceTransactionNumber }}"
                                            >-</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $pedido->CentroVenta }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500; text-wrap: nowrap;"
                                    >{{ number_format($pedido->Kilos, 3) }} kg</td>
                                    <td class="text-end">${{ number_format($pedido->CostoEnvio, 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($pedido->ImportePago, 2) }}</td>
                                    @if ($puedeAccionar)
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                @if ($sourceIdentifier && $status !== 'PROCESADO')
                                                    <button
                                                        type="button"
                                                        class="btn-amber btn-enviar"
                                                        title="Enviar pedido a Oracle"
                                                        data-pedido="{{ $sourceIdentifier }}"
                                                    >
                                                        <i class="bi bi-send"></i> ENVIAR
                                                    </button>
                                                @endif
                                                @if ($status === 'PROCESADO' && $sourceTransactionNumber)
                                                    <div
                                                        class="acciones-oracle buttons-oracle-{{ $sourceTransactionNumber }} d-inline-block"
                                                        data-pedido="{{ $sourceTransactionNumber }}"
                                                    >
                                                        <span class="text-muted">-</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @if ($sourceIdentifier)
                                    <tr
                                        id="msg-{{ $sourceIdentifier }}"
                                        class="bg-light"
                                        @if (empty($oracleData->MENSAJE_ERROR)) style="display: none;" @endif
                                    >
                                        <td
                                            colspan="{{ $columnas }}"
                                            class="py-1 ps-5"
                                        >
                                            <small
                                                class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}">
                                                <strong>
                                                    @if ($oracleData->Transaction_On)
                                                        {{ $oracleData->Transaction_On }} -
                                                    @endif
                                                    {{ $status === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                </strong>
                                                <span id="mensaje-texto-{{ $sourceIdentifier }}">{{ $oracleData->MENSAJE_ERROR }}</span>
                                            </small>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            {{-- Totales --}}
                            @if (count($pedidos) > 0)
                                <tr class="bg-table-totals">
                                    <td
                                        colspan="6"
                                        class="fw-bold text-end"
                                        style="color: var(--text-primary);"
                                    >TOTALES:</td>
                                    <td
                                        class="fw-bold text-end"
                                        style="color: var(--text-primary); text-wrap: nowrap;"
                                    >{{ number_format($kpis['kilos'], 2) }} kg</td>
                                    <td
                                        class="fw-bold text-end"
                                        style="color: var(--text-primary);"
                                    >${{ number_format($kpis['costo_envio'], 2) }}</td>
                                    <td
                                        class="fw-bold text-end"
                                        style="color: var(--text-primary);"
                                    >${{ number_format($kpis['ventas_mes'], 2) }}</td>
                                    @if ($puedeAccionar)
                                        <td></td>
                                    @endif
                                </tr>
                            @else
                                <tr>
                                    <td
                                        colspan="{{ $columnas }}"
                                        class="py-5 text-center"
                                    >
                                        <i
                                            class="bi bi-inbox"
                                            style="font-size: 2.5rem; color: var(--text-muted);"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: var(--text-secondary); font-size: 0.85rem;"
                                        >No hay ventas entregadas en este mes</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    <script>
        // ====================================================================================================
        // ESTATUS Y ACCIONES DE ORACLE, mismas URLs que DashTienda
        // ====================================================================================================
        function fetchStatusOracle(item) {
            const pedido = item.dataset.pedido;
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (!(data.ok && data.dato?.lines)) {
                        item.innerHTML = '<span class="tags-red">Sin datos</span>';
                        return;
                    }

                    let estatusUnicos = [...new Set(data.dato.lines.map(line => line.status))];
                    if (estatusUnicos.length > 1) {
                        estatusUnicos = estatusUnicos.filter(status => status !== 'Canceled');
                    }
                    item.innerHTML = `<span class="tags-green" style="text-wrap:nowrap">${estatusUnicos.join(', ')}</span>`;

                    if (estatusUnicos.length != 1) return;

                    const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                    if (estatusUnicos[0] == 'Awaiting Shipping' && contenedor) {
                        contenedor.innerHTML = '';
                        botonDespachoInventario(contenedor, pedido);
                    }
                    if (estatusUnicos[0] == 'Awaiting Billing' && contenedor) {
                        contenedor.innerHTML = '';
                        botonGenerarFactura(contenedor, pedido);
                    }
                    if (estatusUnicos[0] == 'Closed') {
                        fetchBuscarUUID(pedido, item);
                    }
                })
                .catch(() => item.innerHTML = '<span class="tags-red">Sin datos</span>');
        }

        // Cuando ya esta timbrada la factura de contado se ponen los botones de PDF y XML
        function fetchBuscarUUID(pedido, item) {
            fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/FacturaOracle?Orden=${pedido}`)
                .then(response => response.json())
                .then(data => {
                    const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                    if (!data.ok || !data.dato?.uUidApex) return;

                    const innerSpan = item.querySelector('span');
                    if (innerSpan) innerSpan.textContent = innerSpan.textContent + ' & Timbrado';

                    if (contenedor) {
                        contenedor.innerHTML = `
                            <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${pedido}" target="_blank" class="btn-blue" title="PDF">
                                <i class="bi bi-download"></i> PDF
                            </a>
                            <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden=${pedido}" target="_blank" class="btn-gray" title="XML">
                                <i class="bi bi-download"></i> XML
                            </a>
                        `;
                    }
                });
        }

        // Consulta el estatus hasta que el pedido llegue al estatus esperado despues de una accion
        function getStatusLoop(pedido, estatusSiguiente) {
            const item = document.getElementById(`status-oracle-${pedido}`);
            const reintentar = () => setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);

            fetch(`https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`)
                .then(response => response.json())
                .then(data => {
                    const estatusUnicos = data.ok && data.dato?.lines ? [...new Set(data.dato.lines.map(line => line.status))] : [];
                    if (estatusUnicos.length != 1 || estatusUnicos[0] != estatusSiguiente) return reintentar();

                    item.innerHTML = `<span class="tags-green" style="text-wrap:nowrap">${estatusUnicos[0]}</span>`;
                    const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                    if (estatusSiguiente == 'Awaiting Billing' && contenedor) {
                        contenedor.innerHTML = '';
                        botonGenerarFactura(contenedor, pedido);
                    }
                    if (estatusSiguiente == 'Closed') {
                        fetchBuscarUUID(pedido, item);
                    }
                })
                .catch(reintentar);
        }

        function botonDespachoInventario(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-amber';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> DESPACHO';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Despachando...';
                btn.disabled = true;
                fetch(`https://oracledespachorest.kowi.com.mx/api/PickWave/Despacho?Orden=${pedido}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) getStatusLoop(pedido, 'Awaiting Billing');
                        else btn.innerHTML = '❌ Error';
                    }).catch(() => btn.innerHTML = '❌ Error');
            });
            contenedor.appendChild(btn);
        }

        function botonGenerarFactura(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-blue';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> GENERAR FACTURA';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generando...';
                btn.disabled = true;
                fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/Factura?Orden=${pedido}`, {
                        method: 'POST'
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) getStatusLoop(pedido, 'Closed');
                        else btn.innerHTML = '❌ Error';
                    }).catch(() => btn.innerHTML = '❌ Error');
            });
            contenedor.appendChild(btn);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.status-oracle').forEach(item => fetchStatusOracle(item));
        });

        // ====================================================================================================
        // ENVÍO DE PEDIDOS, usa la misma ruta de DashTienda (Origen=POS)
        // ====================================================================================================
        document.querySelectorAll('.btn-enviar').forEach(button => {
            button.addEventListener('click', async function() {
                const pedidoId = this.dataset.pedido;
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                try {
                    const response = await fetch(`/DashTienda/enviar-pedido/${pedidoId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    actualizarFilaConRespuesta(pedidoId, await response.json(), btn);
                } catch (error) {
                    actualizarFilaConRespuesta(pedidoId, {
                        ok: false,
                        message: error.message
                    }, btn);
                }
            });
        });

        function actualizarFilaConRespuesta(pedidoId, resultado, button) {
            const esExito = resultado.ok;
            const mensaje = resultado.message || (esExito ? 'Procesado correctamente' : 'Error desconocido');

            const spanEstado = document.getElementById(`status-${pedidoId}`);
            if (spanEstado) {
                spanEstado.className = esExito ? 'tags-green' : 'tags-red';
                spanEstado.textContent = esExito ? 'PROCESADO' : 'ERROR';
            }

            const filaMensaje = document.getElementById(`msg-${pedidoId}`);
            if (filaMensaje) {
                filaMensaje.style.display = '';
                const small = filaMensaje.querySelector('small');
                small.className = esExito ? 'text-success' : 'text-danger';
                small.querySelector('strong').textContent = esExito ? 'Mensaje:' : 'Error:';
                document.getElementById(`mensaje-texto-${pedidoId}`).textContent = mensaje;
            }

            if (esExito && resultado.dato?.sourceTransactionNumber) {
                button.parentNode.innerHTML =
                    `<a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn-blue">PDF</a>
                     <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Zip?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn-gray">ZIP</a>`;
            } else {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-send"></i> ENVIAR';
            }
        }
    </script>
</x-page-container>
