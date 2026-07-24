<x-page-container title="Corte por Tiendas">

    {{-- SECCIÓN 1: FILTROS --}}
    <x-card-gradient-header
        icon="receipt"
        title="Cortes por Tiendas"
        subtitle="Resumen de cortes y ventas por tienda"
    >
        <x-slot:buttons>
            <a
                href="/GenerarCorteOraclePDF/{{ request('fecha_fin') }}/{{ request('tienda_id') }}/0"
                class="btn-header-ghost"
                target="_blank"
                title="Descargar corte"
                style="background: #f0fdf4; color: #10b981;"
                onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
            >
                <i class="bi bi-file-text"></i> Descargar corte
            </a>
            <x-header.buttons.refresh-button />
            <x-header.buttons.home-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="{{ route('DashCorte') }}">
            <x-form.group>
                <x-form.select
                    name="tienda_id"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                />
                <x-form.date
                    name="fecha_fin"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :autofocus="true"
                />
                <x-form.text
                    name="pos"
                    label="Pedido"
                    icon="search"
                    placeholder="POS_000000"
                    col="col-md-2"
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

        {{-- SECCIÓN 2: KPIs --}}
        <div class="p-4">
            <div class="row g-3">
                @php
                    $totalMonederoImporte = $totalMonedero->sum('importe');
                    $totalMonederoClientes = $totalMonedero->count();
                    $totalCredito = $creditoSemanal + $creditoQuincenal;
                    $totalTarjeta = $totalTarjetaDebito + $totalTarjetaCredito;
                    $totalTransferenciaEfectivo = $totalTransferencia + $totalEfectivo;
                    $totalGeneral =
                        $totalEfectivo +
                        $totalTransferencia +
                        $totalTarjetaDebito +
                        $totalTarjetaCredito +
                        $creditoSemanal +
                        $creditoQuincenal +
                        $totalMonederoImporte;
                @endphp

                <!-- Dinero Electrónico -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(139, 92, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #7c3aed; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Dinero Electrónico</span>
                                <i
                                    class="bi bi-credit-card"
                                    style="color: #8b5cf6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($totalMonederoImporte, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">{{ $totalMonederoClientes }}
                                cliente(s)</span>
                        </div>
                    </div>
                </div>

                <!-- Crédito -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(20, 184, 166, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #0d9488; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Crédito</span>
                                <i
                                    class="bi bi-calendar3"
                                    style="color: #14b8a6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($totalCredito, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.75rem;">Sem:
                                ${{ number_format($creditoSemanal, 2) }} | Quinc:
                                ${{ number_format($creditoQuincenal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(99, 102, 241, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #4f46e5; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Tarjeta</span>
                                <i
                                    class="bi bi-credit-card-2-front"
                                    style="color: #6366f1; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($totalTarjeta, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.75rem;">Déb:
                                ${{ number_format($totalTarjetaDebito, 2) }} | Créd:
                                ${{ number_format($totalTarjetaCredito, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Transferencia/Efectivo -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(249, 115, 22, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #ea580c; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Transf/Efectivo</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: #f97316; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($totalTransferenciaEfectivo, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.75rem;">Transf:
                                ${{ number_format($totalTransferencia, 2) }} | Efect:
                                ${{ number_format($totalEfectivo, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total General -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Total General</span>
                                <i
                                    class="bi bi-graph-up"
                                    style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($totalGeneral, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Resumen completo del día</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: TABLA DE CORTE --}}
        <div class="px-4 pb-4">
            <div
                class="rounded p-4 shadow-sm"
                style="background: white; border-radius: 12px;"
            >
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5
                            class="mb-1"
                            style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                        >
                            CORTE TIENDA {{ $tiendaActual->NomTienda ?? '' }}
                        </h5>
                        @if ($fechaActual)
                            <small style="color: #64748b; font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($fechaActual)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    {{-- CORTE DE CONTADO --}}
                    @foreach ($cortesContadoOptimizado as $corteTienda)
                        <div class="mb-4">
                            {{-- Encabezado del cliente --}}
                            <div
                                class="d-flex align-items-center mb-2 gap-3 rounded p-3"
                                style="background: #f8fafc;"
                            >
                                <div
                                    class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="background: #eff6ff; width: 36px; height: 36px;"
                                >
                                    <i
                                        class="bi bi-box"
                                        style="color: #3b82f6; font-size: 0.9rem;"
                                    ></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <h6
                                            class="fw-semibold mb-0"
                                            style="color: #0f172a; font-size: 0.9rem;"
                                        >
                                            {{ $corteTienda->Customer->NomClienteCloud ?? 'Cliente' }}
                                        </h6>
                                        @php $oracleData = $corteTienda->OracleData ?? []; @endphp
                                        @if (count($oracleData) > 0)
                                            @foreach ($oracleData as $sourceId => $oracleInfo)
                                                @php
                                                    $statusText = $oracleInfo->Source_Transaction_Number ?? null;
                                                    $statusClass = empty($statusText)
                                                        ? 'tags-red'
                                                        : ($oracleInfo->STATUS == 'ERROR'
                                                            ? 'tags-red'
                                                            : 'tags-blue');
                                                @endphp
                                                <span
                                                    class="{{ $statusClass }}">{{ $statusText ?: 'SIN PEDIDO' }}</span>
                                            @endforeach
                                        @else
                                            <span class="tags-red">SIN PEDIDO</span>
                                        @endif
                                    </div>
                                    @foreach ($corteTienda->OracleData as $sourceId => $oracleInfo)
                                        @if (!empty($oracleInfo->MENSAJE_ERROR))
                                            <div
                                                class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'text-danger' : 'text-success' }}">
                                                <p class="mb-0">
                                                    <span
                                                        style="font-weight: 600">{{ $oracleInfo->Transaction_On }}{{ !empty($oracleInfo->Transaction_On) ? ' - ' : '' }}Mensaje:</span>
                                                    <span style="font-weight: 500">
                                                        {{ $oracleInfo->Source_Transaction_Number }}
                                                        {{ $oracleInfo->MENSAJE_ERROR }}
                                                    </span>
                                                </p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Tabla de productos --}}
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                        <th><i class="bi bi-box me-1"></i>Artículo</th>
                                        <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                        <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                                        <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                        <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sumCantArticulo = 0;
                                        $sumImporte = 0;
                                    @endphp
                                    @foreach ($corteTienda->cortes as $detalleCorte)
                                        <tr>
                                            <td style="font-weight: 500;">{{ $detalleCorte->CodArticulo }}</td>
                                            <td
                                                class="text-truncate"
                                                style="max-width: 200px;"
                                                title="{{ $detalleCorte->NomArticulo }}"
                                            >{{ $detalleCorte->NomArticulo }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                                            <td>
                                                @php $sourceId = $detalleCorte->Source_Transaction_Identifier ?? null; @endphp
                                                @if (empty($sourceId) && $detalleCorte->SolicitudCancelacion != null)
                                                    <span class="tags-red">SOLICITUD CANCELACIÓN</span>
                                                @elseif(empty($sourceId))
                                                    <span class="tags-red">SIN PEDIDO</span>
                                                @else
                                                    <span
                                                        class="{{ $detalleCorte->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}"
                                                    >
                                                        {{ substr_replace($sourceId, '_', 3, 0) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null;
                                                    $status = $oracleInfo->STATUS ?? null;
                                                    $mensajeError = $oracleInfo->MENSAJE_ERROR ?? null;
                                                    $solicitudCancelacion = $detalleCorte->SolicitudCancelacion ?? null;

                                                    if (!empty($solicitudCancelacion)) {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'SIN PROCESAR';
                                                    } elseif (empty($sourceId)) {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'SIN PROCESAR';
                                                    } elseif ($status == 'ERROR') {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'ERROR';
                                                    } elseif ($status == 'PROCESADO') {
                                                        $statusClass = 'tags-green';
                                                        $statusText = 'PROCESADO';
                                                    } elseif ($status == 'EN PROCESO') {
                                                        $statusClass = 'tags-yellow';
                                                        $statusText = 'EN PROCESO';
                                                    } else {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'SIN PROCESAR';
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                {{-- @if (!empty($mensajeError) && $status == 'ERROR')
                                                        <button
                                                            class="btn btn-sm d-flex align-items-center mx-auto mt-1 gap-1"
                                                            style="background: #fef2f2; color: #ef4444; border: none; border-radius: 6px; padding: 2px 8px; font-size: 0.7rem;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#mensajeError{{ $detalleCorte->IdCortesTienda }}"
                                                        >
                                                            <i class="bi bi-info-circle"></i> Ver error
                                                        </button>
                                                        @include('CortesTienda.ModalMensajeErrorOracle')
                                                    @endif --}}
                                            </td>
                                        </tr>
                                        @php
                                            $sumCantArticulo += $detalleCorte->CantArticulo;
                                            $sumImporte += $detalleCorte->ImporteArticulo;
                                        @endphp
                                    @endforeach

                                    {{-- Monedero Electrónico --}}
                                    @foreach ($totalMonedero as $monedero)
                                        @if ($corteTienda->Bill_To == $monedero->Bill_To)
                                            <tr style="background: #f8fafc;">
                                                <td
                                                    colspan="2"
                                                    class="fw-bold text-end"
                                                    style="color: #ef4444;"
                                                >Dinero Electrónico:</td>
                                                <td colspan="3"></td>
                                                <td
                                                    class="fw-bold text-end"
                                                    style="color: #ef4444;"
                                                >${{ number_format($monedero->importe, 2) }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- Subtotales --}}
                                    <tr style="background: #f1f5f9;">
                                        <td
                                            colspan="2"
                                            class="fw-bold text-end"
                                        >SubTotales:</td>
                                        <td class="fw-bold text-end">{{ number_format($sumCantArticulo, 3) }}</td>
                                        <td colspan="2"></td>
                                        <td class="fw-bold text-end">${{ number_format($sumImporte, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                    {{-- CORTE DE SOLICITUDES DE FACTURA --}}
                    @foreach ($cortesSolicitudesOptimizado as $corteTienda)
                        <div class="mb-4">
                            <div
                                class="d-flex align-items-center mb-2 gap-3 rounded p-3"
                                style="background: #f8fafc;"
                            >
                                <div
                                    class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="background: #fef2f2; width: 36px; height: 36px;"
                                >
                                    <i
                                        class="bi bi-person"
                                        style="color: #ef4444; font-size: 0.9rem;"
                                    ></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <h6
                                            class="fw-semibold {{ !is_null($corteTienda->Customer->Editar) ? 'text-danger' : '' }} mb-0"
                                            style="color: #0f172a; font-size: 0.9rem;"
                                        >
                                            {{ $corteTienda->Customer->NomCliente ?? ($corteTienda->Customer->NomClienteCloud ?? 'Cliente') }}
                                        </h6>
                                        @if (!is_null($corteTienda->Customer->Editar))
                                            <span class="tags-red">SIN LIGAR</span>
                                        @endif
                                        @php $oracleData = $corteTienda->OracleData ?? []; @endphp
                                        @if (count($oracleData) > 0)
                                            @foreach ($oracleData as $sourceId => $oracleInfo)
                                                @php
                                                    $statusText = $oracleInfo->Source_Transaction_Number ?? null;
                                                    $statusClass = empty($statusText)
                                                        ? 'tags-red'
                                                        : ($oracleInfo->STATUS == 'ERROR'
                                                            ? 'tags-red'
                                                            : 'tags-blue');
                                                @endphp
                                                <span
                                                    class="{{ $statusClass }}">{{ $statusText ?: 'SIN PEDIDO' }}</span>
                                            @endforeach
                                        @else
                                            <span class="tags-red">SIN PEDIDO</span>
                                        @endif
                                    </div>
                                    @foreach ($corteTienda->OracleData as $sourceId => $oracleInfo)
                                        @if (!empty($oracleInfo->MENSAJE_ERROR))
                                            <div
                                                class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'text-danger' : 'text-success' }}">
                                                <p class="mb-0">
                                                    <span
                                                        style="font-weight: 600">{{ $oracleInfo->Transaction_On }}{{ !empty($oracleInfo->Transaction_On) ? ' - ' : '' }}Mensaje:</span>
                                                    <span style="font-weight: 500">
                                                        {{ $oracleInfo->Source_Transaction_Number }}
                                                        {{ $oracleInfo->MENSAJE_ERROR }}
                                                    </span>
                                                </p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Tabla de productos --}}
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                        <th><i class="bi bi-box me-1"></i>Artículo</th>
                                        <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                        <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                                        <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                        <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sumCantArticulo = 0;
                                        $sumImporte = 0;
                                    @endphp
                                    @foreach ($corteTienda->cortes as $detalleCorte)
                                        <tr>
                                            <td style="font-weight: 500;">{{ $detalleCorte->CodArticulo }}</td>
                                            <td
                                                class="text-truncate"
                                                style="max-width: 200px;"
                                                title="{{ $detalleCorte->NomArticulo }}"
                                            >{{ $detalleCorte->NomArticulo }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                                            <td>
                                                @php $sourceId = $detalleCorte->Source_Transaction_Identifier ?? null; @endphp
                                                @if (empty($sourceId) && $detalleCorte->SolicitudCancelacion != null)
                                                    <span class="tags-red">Solicitud Cancelación</span>
                                                @elseif(empty($sourceId))
                                                    <span class="tags-red">SIN PEDIDO</span>
                                                @else
                                                    @php $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null; @endphp
                                                    <span
                                                        class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'tags-red' : 'tags-blue' }}"
                                                    >
                                                        {{ substr_replace($sourceId, '_', 3, 0) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null;
                                                    $status = $oracleInfo->STATUS ?? null;
                                                    $solicitudCancelacion = $detalleCorte->SolicitudCancelacion ?? null;

                                                    if (!empty($solicitudCancelacion)) {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'CANCELACIÓN SOLICITADA';
                                                    } elseif (empty($sourceId)) {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'SIN PROCESAR';
                                                    } elseif ($status == 'ERROR') {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'ERROR';
                                                    } elseif ($status == 'PROCESADO') {
                                                        $statusClass = 'tags-green';
                                                        $statusText = 'PROCESADO';
                                                    } elseif ($status == 'EN PROCESO') {
                                                        $statusClass = 'tags-yellow';
                                                        $statusText = 'EN PROCESO';
                                                    } else {
                                                        $statusClass = 'tags-red';
                                                        $statusText = 'SIN PROCESAR';
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                        </tr>
                                        @php
                                            $sumCantArticulo += $detalleCorte->CantArticulo;
                                            $sumImporte += $detalleCorte->ImporteArticulo;
                                        @endphp
                                    @endforeach
                                    <tr style="background: #f1f5f9;">
                                        <td
                                            colspan="2"
                                            class="fw-bold text-end"
                                        >SubTotales:</td>
                                        <td class="fw-bold text-end">{{ number_format($sumCantArticulo, 3) }}</td>
                                        <td colspan="2"></td>
                                        <td class="fw-bold text-end">${{ number_format($sumImporte, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                    {{-- Estado vacío --}}
                    @if (
                        (empty($cortesContadoOptimizado) || count($cortesContadoOptimizado) == 0) &&
                            (empty($cortesSolicitudesOptimizado) || count($cortesSolicitudesOptimizado) == 0))
                        <div class="py-5 text-center">
                            <i
                                class="bi bi-inbox"
                                style="font-size: 2.5rem; color: #94a3b8;"
                            ></i>
                            <p
                                class="mt-2"
                                style="color: #64748b; font-size: 0.85rem;"
                            >No hay ventas para mostrar</p>
                            <p style="color: #94a3b8; font-size: 0.8rem;">Prueba cambiando las fechas o los filtros de
                                búsqueda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
