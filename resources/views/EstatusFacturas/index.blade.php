<x-page-container title="Estatus Pedidos">
    <x-card-gradient-header
        icon="cloud-upload"
        title="Estatus Pedidos"
        subtitle="Consulta de pedidos por tipo de orden y fecha"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form
            action="{{ route('facturasdiarias.index') }}"
            id="formFiltros"
            method="GET"
        >
            <x-form.group>
                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha')"
                    autofocus
                />

                {{-- NUEVO FILTRO: Tipo de factura --}}
                <x-form.select
                    name="tipo_factura"
                    label="Tipo de Factura"
                    icon="file-earmark"
                    col="col-md-2"
                    :options="[
                        'todos' => 'Todos',
                        'pos_contado' => 'Pos Contado',
                        'pos_factura' => 'Pos Factura',
                        'rutas_contado' => 'Rutas Contado',
                        'rutas_factura' => 'Rutas Factura',
                    ]"
                    :value="request('tipo_factura')"
                    placeholder="Seleccionar tipo..."
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
        <div
            class="rounded p-4 shadow-sm"
            style="background: white; border-radius: 12px;"
        >
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5
                        class="mb-1"
                        style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                    >
                        PEDIDOS ENCONTRADOS
                    </h5>
                    <small
                        style="color: #64748b; font-size: 0.85rem;"
                        class="contador-resultados"
                    >
                        {{ $pedidos->count() }} resultados
                        @if (request('pedido'))
                            | Pedido: {{ request('pedido') }}
                        @else
                            | Fecha:
                            {{ request('fecha') ? \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y') : 'Sin fecha' }}
                            @if (request('tipo_factura'))
                                | Tipo: {{ request('tipo_factura') }}
                            @endif
                        @endif
                    </small>
                </div>
                <!-- Indicador de carga global -->
                @if (count($pedidos) > 0)
                    <div
                        id="cargandoOracle"
                        style="display: none;"
                    >
                        <span class="px-3 py-2">
                            <span
                                class="spinner-border spinner-border-sm me-2"
                                role="status"
                            ></span>
                            Consultando Oracle...
                            <span
                                class="ms-1"
                                id="contadorConsultas"
                            >0</span>
                        </span>
                    </div>
                @endif
            </div>

            <div class="table-responsive">
                <table
                    class="table-hover table-custom table"
                    id="tablaFacturas"
                >
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
                            $pedidosVisibles = 0;
                        @endphp
                        @forelse ($pedidos as $pedido)
                            @php
                                $status = $pedido->STATUS ?? null;
                                $mensajeError = $pedido->MENSAJE_ERROR ?? null;
                                $transactionOn = $pedido->Transaction_On ?? null;
                                $sourceTransactionNumber = $pedido->Source_Transaction_Number ?? null;
                                $uuid = $pedido->UUID ?? null;
                                $factura = $pedido->FACTURA ?? null;
                                $totalCantidad += $pedido->cantidad_total ?? 0;
                                $totalVenta += $pedido->venta_total ?? 0;
                                $pedidosVisibles++;
                            @endphp
                            <tr id="row-{{ $sourceTransactionNumber ?? 'temp-' . $loop->index }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="background: #eff6ff; width: 28px; height: 28px;"
                                        >
                                            @if ($factura)
                                                <i
                                                    class="bi bi-file-earmark-check"
                                                    style="color: #3b82f6; font-size: 0.75rem;"
                                                ></i>
                                            @else
                                                <i
                                                    class="bi bi-cash"
                                                    style="color: #10b981; font-size: 0.75rem;"
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
                                <td>
                                    <span style="color: #475569; font-size: 0.85rem;">
                                        {{ $transactionOn ? \Carbon\Carbon::parse($transactionOn)->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold tags-blue">
                                        {{ $sourceTransactionNumber }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        id="status-{{ $sourceTransactionNumber }}"
                                        class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                    >
                                        @if ($status === 'PROCESADO')
                                            <i class="bi bi-check-circle me-1"></i>PROCESADO
                                        @elseif($status === 'ERROR')
                                            <i class="bi bi-x-circle me-1"></i>ERROR
                                        @else
                                            <i class="bi bi-exclamation-circle me-1"></i>SIN PROCESAR
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        id="status-oracle-{{ $sourceTransactionNumber }}"
                                        class="{{ $status === 'PROCESADO' ? 'status-oracle' : '' }} text-muted"
                                        data-pedido="{{ $sourceTransactionNumber }}"
                                        data-uuid="{{ $uuid }}"
                                        data-cargando="false"
                                    >
                                        @if ($status === 'PROCESADO')
                                            <span
                                                class="spinner-border spinner-border-sm text-primary"
                                                role="status"
                                            ></span>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span style="font-weight: 500;">
                                        {{ number_format($pedido->cantidad_total ?? 0) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span style="font-weight: 500;">
                                        ${{ number_format($pedido->venta_total ?? 0, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        {{-- Solo mostrar ENVIAR si no está procesado --}}
                                        @if ($status !== 'PROCESADO')
                                            <button
                                                type="button"
                                                id="btnEnviarPedido{{ $sourceTransactionNumber }}"
                                                class="btn btn-sm btn-enviar d-flex align-items-center btn-animated gap-1"
                                                style="background: #fffbeb; color: #f59e0b; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                title="Enviar pedido a Oracle"
                                                data-pedido="{{ $sourceTransactionNumber }}"
                                                data-row-id="row-{{ $sourceTransactionNumber }}"
                                                data-original-status="{{ $status }}"
                                                data-original-mensaje="{{ $mensajeError ?? '' }}"
                                            >
                                                <i class="bi bi-send"></i> ENVIAR
                                            </button>
                                        @endif

                                        {{-- Contenedor para botones dinámicos de Oracle --}}
                                        @if ($status === 'PROCESADO')
                                            <div
                                                class="acciones-oracle buttons-oracle-{{ $sourceTransactionNumber }} d-inline-block d-flex gap-2"
                                                data-pedido="{{ $sourceTransactionNumber }}"
                                            ></div>
                                        @endif

                                        <button
                                            type="button"
                                            id="btnReintentar{{ $sourceTransactionNumber }}"
                                            class="btn btn-sm btn-reintentar d-none align-items-center btn-animated gap-1"
                                            style="background: #e0f2fe; color: #0284c7; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                            title="Reintentar consulta"
                                            data-pedido="{{ $sourceTransactionNumber }}"
                                            data-uuid-local="{{ $uuid }}"
                                        >
                                            <i class="bi bi-arrow-repeat"></i> REINTENTAR
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            {{-- Sub-row para mensaje de error --}}
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
                                    <i
                                        class="bi bi-funnel"
                                        style="font-size: 2.5rem; color: #94a3b8;"
                                    ></i>
                                    <h6 style="color: #0f172a;">Aplique filtros para consultar</h6>
                                    <p class="text-muted">
                                        Busque por número de pedido o seleccione tipo de orden y fecha
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>

    <style>
        /* Animación para el indicador de carga */
        #cargandoOracle {
            background: #0d6efd;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        /* Estilo para filas en proceso de carga */
        tr.cargando {
            opacity: 0.7;
            background-color: #f8f9fa !important;
        }

        /* Spinner en la celda de Oracle */
        .status-oracle .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        /* Transiciones suaves para los botones */
        .btn-animated {
            transition: all 0.3s ease;
        }

        .btn-animated:hover {
            transform: scale(1.05);
        }

        .btn-animated:active {
            transform: scale(0.95);
        }

        /* Badges de estatus */
        .tags-green {
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .tags-red {
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .tags-yellow {
            background: #fef9c3;
            color: #ca8a04;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .tags-blue {
            background: #dbeafe;
            color: #2563eb;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
    </style>

    <script>
        // ====================================================================================================
        // BOTÓN REINTENTAR - EVENTO CLICK
        // ====================================================================================================

        document.querySelectorAll('.btn-reintentar').forEach(button => {
            button.addEventListener('click', function() {
                const pedido = this.dataset.pedido;
                const uuidLocal = this.dataset.uuidLocal || '';
                const item = document.getElementById(`status-oracle-${pedido}`);

                if (!item) {
                    console.warn(`⚠️ No se encontró la celda Oracle para pedido ${pedido}`);
                    return;
                }

                console.log(`🔄 Reintentando consulta para pedido ${pedido}`);

                // Mostrar spinner
                item.innerHTML =
                    `<span class="spinner-border spinner-border-sm text-primary" role="status"></span>`;
                item.dataset.cargando = 'true';

                // Ocultar el botón mientras se procesa
                this.classList.add('d-none');

                // Ejecutar la consulta UUID
                fetchBuscarUUIDConTimeout(pedido, item, uuidLocal);
            });
        });

        function mostrarBotonesReintentar() {
            console.log('andamos en reintentar');
            // Buscar todas las celdas de Oracle que aún tienen spinner (data-cargando="true")
            document.querySelectorAll('.status-oracle[data-cargando]').forEach(e => {
                if (e.querySelector('.spinner-border')) {
                    const pedido = e.dataset.pedido;
                    const btnReintentar = document.getElementById(`btnReintentar${pedido}`);
                    if (btnReintentar) {
                        btnReintentar.classList.remove('d-none');
                        console.log(`🔁 Botón Reintentar mostrado para pedido ${pedido}`);

                        // ✅ EJECUTAR AUTOMÁTICAMENTE EL REINTENTO
                        // Mostrar spinner
                        e.innerHTML =
                            `<span class="spinner-border spinner-border-sm text-primary" role="status"></span>`;
                        e.dataset.cargando = 'true';

                        // Ocultar el botón mientras se procesa
                        btnReintentar.classList.add('d-none');

                        // Ejecutar la consulta UUID
                        fetchBuscarUUIDConTimeout(pedido, e, uuidLocal);
                    }
                }
            });
        }

        // ====================================================================================================
        // CONFIGURACIÓN DE PROCESAMIENTO POR LOTES
        // ====================================================================================================

        const CONFIG = {
            TAMANO_LOTE: 10, // Procesar 10 consultas a la vez
            DELAY_ENTRE_LOTES: 800, // Esperar 800ms entre lotes
            TIMEOUT_CONSULTA: 20000 // Timeout de 20 segundos por consulta
        };

        // Contador de consultas pendientes
        let consultasPendientes = 0;
        let colaProcesamiento = [];
        let procesando = false;

        // ====================================================================================================
        // INDICADORES DE CARGA - BARRA DE PROGRESO
        // ====================================================================================================

        // Variables para la barra de progreso
        let totalCompletados = 0;
        let totalEstimado = 0;

        // Al inicio, después de las variables globales
        function mostrarPreparando() {
            const indicador = document.getElementById('cargandoOracle');
            if (indicador) {
                indicador.style.display = 'inline-flex';
            }
        }

        function mostrarBarraProgreso() {
            const indicador = document.getElementById('cargandoOracle');
            if (!indicador) return;

            // Calcular total: elementos en cola + consultas activas + ya completados
            const totalActual = colaProcesamiento.length + consultasPendientes + totalCompletados;

            // Si el total estimado es 0, usar el total actual
            if (totalEstimado === 0) {
                totalEstimado = totalActual;
            }

            // Si el total actual es mayor que el estimado, actualizar el estimado
            if (totalActual > totalEstimado) {
                totalEstimado = totalActual;
            }

            const porcentaje = totalEstimado > 0 ? Math.round((totalCompletados / totalEstimado) * 100) : 0;

            indicador.style.display = 'inline-flex';
            indicador.innerHTML = `
                <span class="badge bg-primary text-white px-3 py-2" style="min-width: 280px; animation: none;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        <span>Procesando...</span>
                        <span class="fw-bold">${totalCompletados}/${totalEstimado}</span>
                        <span class="badge bg-light text-dark">${porcentaje}%</span>
                    </div>
                    <div class="progress mt-1" style="height: 6px; width: 100%;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar"
                            style="width: ${porcentaje}%;"
                            aria-valuenow="${porcentaje}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-light" style="opacity:0.7; font-size:0.6rem; display:block; margin-top:2px;">
                        ${consultasPendientes} en proceso | ${colaProcesamiento.length} pendientes
                    </small>
                </span>
            `;
        }

        function ocultarIndicadorGlobal() {
            consultasPendientes--;

            if (consultasPendientes <= 0) {
                consultasPendientes = 0;
            }

            // Incrementar completados
            totalCompletados++;

            // Actualizar barra
            mostrarBarraProgreso();

            // Si ya no hay más elementos en cola y no hay consultas pendientes
            if (colaProcesamiento.length === 0 && consultasPendientes === 0) {
                const indicador = document.getElementById('cargandoOracle');
                if (indicador) {
                    // Mostrar mensaje de completado
                    indicador.innerHTML = `
                        <span class="text-white px-3 py-2" style="animation: none;">
                            <i class="bi bi-check-circle me-2"></i>
                            ¡Completado! ${totalCompletados} consultas procesadas
                        </span>
                    `;
                    mostrarBotonesReintentar();
                    setTimeout(() => {
                        indicador.style.display = 'none';
                    }, 3000);
                }
            }
        }

        // ====================================================================================================
        // FUNCIONES DE CONSULTA CON TIMEOUT
        // ====================================================================================================

        function fetchStatusOracleConTimeout(item, timeoutId, resolve) {
            const pedido = item.dataset.pedido;
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;

            // Mostrar spinner en la celda
            item.innerHTML = `<span class="spinner-border spinner-border-sm text-primary" role="status"></span>`;
            item.dataset.cargando = 'true';

            // Incrementar contador de consultas
            consultasPendientes++;
            mostrarBarraProgreso();

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    clearTimeout(timeoutId);
                    if (data.ok && data.dato?.lines) {
                        const estatusLineas = data.dato.lines.map(line => line.status);
                        const estatusUnicos = [...new Set(estatusLineas)];

                        if (estatusUnicos.length > 1) {
                            item.innerHTML = `<span class="tags-green">${estatusUnicos.join(', ')}</span>`;
                            if (estatusUnicos.includes('Closed') || estatusUnicos.includes('Canceled')) {
                                fetchBuscarUUIDConTimeout(pedido, item);
                            } else {
                                item.dataset.cargando = 'false';
                            }
                        } else if (estatusUnicos.length == 1) {
                            let estatusPedido = estatusUnicos[0];
                            item.innerHTML = `<span class="tags-green">${estatusPedido}</span>`;
                            const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);

                            if (estatusPedido == 'Awaiting Shipping') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonDespachoInventario(contenedor, pedido);
                                }
                                item.dataset.cargando = 'false';
                            } else if (estatusPedido == 'Awaiting Billing') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonGenerarFactura(contenedor, pedido);
                                }
                                item.dataset.cargando = 'false';
                            } else if (estatusPedido == 'Canceled') {
                                // Cancelado → ocultar directamente
                                const row = document.getElementById(`row-${pedido}`);
                                const msgRow = document.getElementById(`msg-${pedido}`);
                                if (row) {
                                    row.style.display = 'none';
                                    console.log(`🗑️ Fila ${pedido} ocultada (Cancelado)`);
                                }
                                if (msgRow) {
                                    msgRow.style.display = 'none';
                                }
                                actualizarContador();
                                item.dataset.cargando = 'false';
                            } else if (estatusPedido == 'Closed') {
                                const uuidLocal = item.dataset.uuid || '';
                                fetchBuscarUUIDConTimeout(pedido, item, uuidLocal);
                            } else {
                                item.dataset.cargando = 'false';
                            }
                        }
                    } else {
                        item.innerHTML = '<span class="tags-red">Sin datos</span>';
                        item.dataset.cargando = 'false';
                    }
                })
                .catch(() => {
                    clearTimeout(timeoutId);
                    item.innerHTML = '<span class="text-muted">-</span>';
                    item.dataset.cargando = 'false';
                })
                .finally(() => {
                    ocultarIndicadorGlobal();
                    resolve();
                });
        }

        function fetchBuscarUUIDConTimeout(pedido, item, uuidLocal) {
            const apiUrl = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/FacturaOracle?Orden=${pedido}`;

            if (item && !item.innerHTML.includes('spinner')) {
                item.innerHTML = `<span class="spinner-border spinner-border-sm text-primary" role="status"></span>`;
            }

            consultasPendientes++;
            mostrarBarraProgreso();

            const timeoutId = setTimeout(() => {
                console.warn(`⏰ Timeout UUID para pedido ${pedido}`);
                item.innerHTML = `<span class="tags-yellow">Closed (Timeout)</span>`;
                item.dataset.cargando = 'false';
                resolve();
            }, CONFIG.TIMEOUT_CONSULTA);

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    clearTimeout(timeoutId);
                    if (data.ok) {
                        const uuidApex = data.dato?.uUidApex || '';
                        const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                        const row = document.getElementById(`row-${pedido}`);
                        const msgRow = document.getElementById(`msg-${pedido}`);

                        if (uuidApex && uuidApex.trim() !== '') {
                            // ✅ Validar SOLO si UUID Local existe y es DIFERENTE
                            if (uuidLocal && uuidLocal.trim() !== '' && uuidLocal.trim() !== uuidApex.trim()) {
                                // ❌ INCIDENCIA: UUIDs diferentes
                                item.innerHTML = `<span class="tags-red">Closed & !UUID Apex</span>`;
                                mostrarIncidenciaUUID(pedido, uuidLocal, uuidApex);
                                // ❌ NO ocultar la fila
                            } else {
                                // ✅ UUIDs coinciden o Local vacío → timbrado correcto
                                item.innerHTML = `<span class="tags-green">Closed & Timbrado</span>`;
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonesPDFXML(contenedor, pedido);
                                }
                                // Ocultar fila
                                if (row) {
                                    row.style.display = 'none';
                                    console.log(`✅ Fila ${pedido} ocultada (Timbrado)`);
                                }
                                if (msgRow) {
                                    msgRow.style.display = 'none';
                                }
                                actualizarContador();
                            }
                        } else {
                            // Sin UUID Apex → pendiente de timbrado
                            item.innerHTML = `<span class="tags-yellow">Closed</span>`;
                            mostrarSinTimbrar(pedido);
                        }
                    }
                    item.dataset.cargando = 'false';
                })
                .catch(() => {
                    clearTimeout(timeoutId);
                    console.error(`❌ Error al buscar UUID para pedido ${pedido}`);
                    item.dataset.cargando = 'false';
                })
                .finally(() => {
                    ocultarIndicadorGlobal();
                });
        }

        function mostrarIncidenciaUUID(pedido, uuidLocal, uuidApex) {
            const mensajeContainer = document.getElementById('mensaje-container-' + pedido);
            if (mensajeContainer) {
                // ✅ Mantener el contenido existente y agregar la incidencia
                const contenidoActual = mensajeContainer.innerHTML;

                mensajeContainer.innerHTML = `
                    ${contenidoActual} <br>
                    <b>UUID Local:</b> <span class="text-success">${uuidLocal}</span> <br>
                    <b class="text-danger">UUID Apex:</b> <span class="text-danger">${uuidApex}</span>
                `;
            }
        }

        // ====================================================================================================
        // PROCESAMIENTO POR LOTES
        // ====================================================================================================

        function procesarLote() {
            if (colaProcesamiento.length === 0) {
                procesando = false;

                // Si no hay consultas pendientes, ocultar el indicador
                if (consultasPendientes === 0) {
                    const indicador = document.getElementById('cargandoOracle');
                    if (indicador) {
                        indicador.innerHTML = `
                            <span class="badge bg-success text-white px-3 py-2" style="animation: none;">
                                <i class="bi bi-check-circle me-2"></i>
                                ¡Completado! ${totalCompletados} consultas procesadas
                            </span>
                        `;
                        mostrarBotonesReintentar();

                        setTimeout(() => {
                            indicador.style.display = 'none';
                        }, 3000);
                    }
                }
                return;
            }

            procesando = true;

            const lote = colaProcesamiento.splice(0, CONFIG.TAMANO_LOTE);

            console.log(`📦 Procesando lote de ${lote.length} elementos (${colaProcesamiento.length} restantes)`);

            const promesas = lote.map(item => {
                return new Promise((resolve) => {
                    const timeoutId = setTimeout(() => {
                        console.warn(`⏰ Timeout para pedido ${item.dataset.pedido}`);
                        item.innerHTML = '<span class="tags-red">Timeout</span>';
                        item.dataset.cargando = 'false';
                        // ✅ Marcar como completado aunque haya timeout
                        totalCompletados++;
                        mostrarBarraProgreso();
                        resolve();
                    }, CONFIG.TIMEOUT_CONSULTA);

                    fetchStatusOracleConTimeout(item, timeoutId, resolve);
                });
            });

            Promise.all(promesas).then(() => {
                setTimeout(() => {
                    procesarLote();
                }, CONFIG.DELAY_ENTRE_LOTES);
            });
        }

        function actualizarFilasOracle() {
            const elementos = document.querySelectorAll('.status-oracle');

            if (elementos.length === 0) {
                console.log('ℹ️ No hay elementos para procesar');
                return;
            }

            console.log(`🔄 Iniciando procesamiento de ${elementos.length} elementos`);

            // ✅ Resetear contadores
            colaProcesamiento = [];
            procesando = false;
            totalCompletados = 0;
            totalEstimado = elementos.length * 2; // Estimación inicial (máximo)

            // Agregar elementos a la cola
            elementos.forEach(item => {
                if (item.dataset.cargando === 'false') {
                    colaProcesamiento.push(item);
                }
            });

            console.log(`📋 ${colaProcesamiento.length} elementos en cola para procesar`);

            // Mostrar barra de progreso inicial
            mostrarBarraProgreso();

            if (colaProcesamiento.length > 0) {
                procesarLote();
            }
        }

        // ====================================================================================================
        // FUNCIONES DE ACTUALIZACIÓN
        // ====================================================================================================

        function actualizarContador() {
            const filasVisibles = document.querySelectorAll(
                '#tablaFacturas tbody tr[id^="row-"]:not([style*="display: none"])'
            );
            const contador = document.querySelector('.contador-resultados');
            if (contador) {
                const totalVisibles = filasVisibles.length;
                const textoActual = contador.textContent;
                const partes = textoActual.match(/^(\d+)\s*resultados(.*)$/);
                if (partes) {
                    contador.textContent = `${totalVisibles} resultados${partes[2] || ''}`;
                }
            }
        }

        // ====================================================================================================
        // FUNCIONES DE BOTONES (DESPACHO, FACTURA, PDF, XML)
        // ====================================================================================================

        function botonDespachoInventario(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm d-flex align-items-center gap-1 btn-animated';
            btn.style.cssText =
                'background:#fffbeb;color:#f59e0b;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;white-space:nowrap;';
            btn.innerHTML = '<i class="bi bi-send"></i> DESPACHO';

            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Despachando...';
                btn.disabled = true;

                fetch(`https://oracledespachorest.kowi.com.mx/api/PickWave/Despacho?Orden=${pedido}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) {
                            getStatusLoop(pedido, 'Awaiting Billing');
                            btn.innerHTML = '✅ Despachado';
                        } else {
                            btn.innerHTML = '❌ Error';
                            btn.disabled = false;
                        }
                    })
                    .catch(() => {
                        btn.innerHTML = '❌ Error';
                        btn.disabled = false;
                    });
            });

            contenedor.appendChild(btn);
        }

        function botonGenerarFactura(contenedor, pedido) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm d-flex align-items-center gap-1 btn-animated';
            btn.style.cssText =
                'background:#eff6ff;color:#3b82f6;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;white-space:nowrap;';
            btn.innerHTML = '<i class="bi bi-send"></i> GENERAR FACTURA';

            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generando...';
                btn.disabled = true;

                fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/Factura?Orden=${pedido}`, {
                        method: 'POST'
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) {
                            getStatusLoop(pedido, 'Closed');
                            btn.innerHTML = '✅ Generada';
                        } else {
                            btn.innerHTML = '❌ Error';
                            btn.disabled = false;
                        }
                    })
                    .catch(() => {
                        btn.innerHTML = '❌ Error';
                        btn.disabled = false;
                    });
            });

            contenedor.appendChild(btn);
        }

        function getStatusLoop(pedido, estatusSiguiente) {
            const item = document.getElementById(`status-oracle-${pedido}`);
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;

            // Mostrar spinner mientras se espera
            if (item && !item.innerHTML.includes('spinner')) {
                item.innerHTML = `<span class="spinner-border spinner-border-sm text-primary" role="status"></span>`;
                consultasPendientes++;
                mostrarBarraProgreso();
            }

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.ok && data.dato?.lines) {
                        const estatusLineas = data.dato.lines.map(line => line.status);
                        const estatusUnicos = [...new Set(estatusLineas)];

                        if (estatusUnicos.length == 1) {
                            let estatusPedido = estatusUnicos[0];
                            item.innerHTML = `<span class="tags-green">${estatusPedido}</span>`;
                            const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);

                            if (estatusPedido == 'Awaiting Billing' && estatusSiguiente == 'Awaiting Billing') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonGenerarFactura(contenedor, pedido);
                                }
                                item.dataset.cargando = 'false';
                                ocultarIndicadorGlobal();
                            } else if (estatusPedido == 'Closed' && estatusSiguiente == 'Closed') {
                                // Llamar a fetchBuscarUUIDConTimeout (ya no necesita resolve)
                                fetchBuscarUUIDConTimeout(pedido, item);
                            } else {
                                setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                            }
                        } else {
                            setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                        }
                    } else {
                        setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                    }
                })
                .catch(() => {
                    setTimeout(() => getStatusLoop(pedido, estatusSiguiente), 3000);
                });
        }

        function botonesPDFXML(contenedor, pedido) {
            // Botón PDF
            const btnPDF = document.createElement('a');
            btnPDF.className = 'btn btn-sm d-flex align-items-center btn-animated gap-1';
            btnPDF.style.cssText =
                'background:#eff6ff;color:#3b82f6;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;';
            btnPDF.innerHTML = '<i class="bi bi-file-pdf"></i> PDF';
            btnPDF.href = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${pedido}`;
            btnPDF.target = '_blank';
            btnPDF.title = 'Descargar PDF';

            // Botón XML
            const btnXML = document.createElement('a');
            btnXML.className = 'btn btn-sm d-flex align-items-center btn-animated gap-1';
            btnXML.style.cssText =
                'background:#f1f5f9;color:#475569;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;';
            btnXML.innerHTML = '<i class="bi bi-file-code"></i> XML';
            btnXML.href = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden=${pedido}`;
            btnXML.target = '_blank';
            btnXML.title = 'Descargar XML';

            contenedor.appendChild(btnPDF);
            contenedor.appendChild(btnXML);
        }

        function mostrarSinTimbrar(pedido) {
            let msgRow = document.getElementById('msg-' + pedido);
            const mainRow = document.getElementById('row-' + pedido);

            if (!msgRow && mainRow) {
                msgRow = document.createElement('tr');
                msgRow.id = 'msg-' + pedido;
                msgRow.className = 'bg-light';
                msgRow.innerHTML = `<td colspan="8" class="py-2 ps-5">
                <small id="mensaje-container-${pedido}" class="text-warning">
                    <span id="uuid-info-${pedido}"></span>
                </small>
            </td>`;
                mainRow.after(msgRow);
            }

            const uuidInfoSpan = document.getElementById('uuid-info-' + pedido);
            if (uuidInfoSpan) {
                uuidInfoSpan.innerHTML = `
                <i class="bi bi-info-circle me-1"></i>
                Pedido cerrado, pendiente de timbrado
            `;
            }

            const mensajeContainer = document.getElementById('mensaje-container-' + pedido);
            if (mensajeContainer) {
                mensajeContainer.className = 'text-success';
            }
        }

        // ====================================================================================================
        // INICIALIZACIÓN
        // ====================================================================================================

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Inicializando página...');

            document.querySelectorAll('#tablaFacturas tbody tr[id^="row-"]').forEach(row => {
                row.style.display = '';
            });

            actualizarContador();

            // ✅ Mostrar "Preparando consultas..."
            mostrarPreparando();

            setTimeout(function() {
                console.log('🚀 Iniciando carga de Oracle...');
                actualizarFilasOracle();
            }, 800);
        });

        // ====================================================================================================
        // ENVÍO DE PEDIDOS (BTN-ENVIAR)
        // ====================================================================================================

        document.querySelectorAll('.btn-enviar').forEach(button => {
            button.addEventListener('click', async function() {
                const pedidoId = this.getAttribute('data-pedido');
                const rowId = this.getAttribute('data-row-id');
                const btn = this;
                const row = document.getElementById(rowId);

                // Deshabilitar botón y mostrar spinner
                btn.disabled = true;
                const origHTML = btn.innerHTML;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Enviando...';

                // Mostrar indicador de carga en la fila
                const statusCell = row.querySelector('td:nth-child(4)');
                if (statusCell) {
                    statusCell.innerHTML = `
                    <span class="tags-yellow">
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        ENVIANDO...
                    </span>
                `;
                }

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
                    const statusCell2 = cells[3];
                    const oracleCell = cells[4];
                    const accionesCell = cells[7];
                    const msgRow = document.getElementById('msg-' + pedidoId);

                    if (result.ok) {
                        // Actualizar estatus a PROCESADO
                        statusCell2.innerHTML = `
                        <span id="status-${pedidoId}" class="tags-green">
                            <i class="bi bi-check-circle me-1"></i>PROCESADO
                        </span>
                    `;

                        // Inicializar celda de Oracle con spinner
                        oracleCell.innerHTML = `
                        <span id="status-oracle-${pedidoId}"
                              class="status-oracle text-muted"
                              data-pedido="${pedidoId}"
                              data-cargando="true">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        </span>
                    `;

                        // Agregar a la cola de procesamiento
                        setTimeout(() => {
                            const oracleItem = document.getElementById(
                                `status-oracle-${pedidoId}`);
                            if (oracleItem) {
                                colaProcesamiento.push(oracleItem);
                                if (!procesando) {
                                    procesarLote();
                                }
                            }
                        }, 2000);

                        // Actualizar mensaje
                        if (result.message) {
                            const mensajeHTML = `
                            <strong id="mensaje-titulo-${pedidoId}">Mensaje:</strong>
                            <span id="mensaje-texto-${pedidoId}">${result.message}</span>
                        `;

                            if (msgRow) {
                                msgRow.querySelector('td small').innerHTML = mensajeHTML;
                                msgRow.querySelector('td small').className = 'text-success';
                                msgRow.className = 'bg-light';
                            } else {
                                const newMsgRow = document.createElement('tr');
                                newMsgRow.id = 'msg-' + pedidoId;
                                newMsgRow.className = 'bg-light';
                                newMsgRow.innerHTML = `
                                <td colspan="8" class="py-2 ps-5">
                                    <small id="mensaje-container-${pedidoId}" class="text-success">
                                        ${mensajeHTML}
                                    </small>
                                </td>`;
                                row.after(newMsgRow);
                            }
                        }

                        // Actualizar columna de acciones
                        accionesCell.innerHTML = `
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <div class="acciones-oracle buttons-oracle-${pedidoId} d-inline-block d-flex gap-2"
                                 data-pedido="${pedidoId}">
                            </div>
                        </div>
                    `;

                        btn.style.display = 'none';

                    } else {
                        // Error
                        statusCell2.innerHTML = `
                        <span id="status-${pedidoId}" class="tags-red">
                            <i class="bi bi-x-circle me-1"></i>ERROR
                        </span>
                    `;

                        const mensajeHTML = `
                        <strong id="mensaje-titulo-${pedidoId}">Error:</strong>
                        <span id="mensaje-texto-${pedidoId}">${result.message}</span>
                    `;

                        if (msgRow) {
                            msgRow.querySelector('td small').innerHTML = mensajeHTML;
                            msgRow.querySelector('td small').className = 'text-danger';
                            msgRow.className = 'bg-light';
                        } else {
                            const newMsgRow = document.createElement('tr');
                            newMsgRow.id = 'msg-' + pedidoId;
                            newMsgRow.className = 'bg-light';
                            newMsgRow.innerHTML = `
                            <td colspan="8" class="py-2 ps-5">
                                <small id="mensaje-container-${pedidoId}" class="text-danger">
                                    ${mensajeHTML}
                                </small>
                            </td>`;
                            row.after(newMsgRow);
                        }

                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                        console.error('❌ Error en el envío:', result.message);
                    }

                } catch (error) {
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                    console.error('❌ Error de conexión:', error);

                    const statusCell = row.querySelector('td:nth-child(4)');
                    if (statusCell) {
                        statusCell.innerHTML = `
                        <span class="tags-red">
                            <i class="bi bi-x-circle me-1"></i>ERROR DE CONEXIÓN
                        </span>
                    `;
                    }
                }
            });
        });
    </script>
</x-page-container>
