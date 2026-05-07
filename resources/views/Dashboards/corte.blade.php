@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Corte por Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <!--CORTE DIARIO DE TIENDA-->
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Cortes por Tiendas" />
                <div class="d-flex gap-2">
                    <!-- Tamaño mediano -->
                    <x-filters.buttons.link-button
                        href="/GenerarCorteOraclePDF/{{ request('fecha_fin') }}/{{ request('tienda_id') }}/0"
                        text="Descargar reporte"
                        icon="components.icons.file-text"
                        color="success"
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="tienda_id"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha"
                        :value="request('fecha_fin')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.text-input
                        name="pos"
                        label="Pedido"
                        placeholder="Buscar por Pedido POS_000000"
                    />
                    <x-filters.inputs.checkbox-input
                        name="detallado"
                        label="Detallado"
                        :checked="request('detallado') == 'on'"
                        helperText="Ver detallado"
                    />
                </x-filters.filter-group>
                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    {{-- <x-filters.buttons.advanced-button
                        :active="$filtrosAvanzadosActivos"
                        :hasBadge="true"
                    /> --}}
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN 2: KPIs -->
        <div class="flex-shrink-0">
            <div class="row g-4">
                <!-- Dinero Electrónico -->
                @php
                    $totalMonederoImporte = $totalMonedero->sum('importe');
                    $totalMonederoClientes = $totalMonedero->count();
                @endphp

                <x-kpi.kpi-card
                    title="Dinero Electrónico"
                    :value="$totalMonederoImporte"
                    :subtitle="$totalMonederoClientes . ' cliente(s)'"
                    color="purple"
                    icon="components.icons.credit-card"
                    currency="true"
                    colClass="col-xl-4 col-md-4 col-sm-6 col-6"
                />

                <!-- Crédito -->
                @php
                    $totalCredito = $creditoSemanal + $creditoQuincenal;
                @endphp

                <x-kpi.kpi-card
                    title="Crédito"
                    :value="$totalCredito"
                    :subtitleHtml="'Semanal: $' .
                        number_format($creditoSemanal, 2) .
                        '<br>Quincenal: $' .
                        number_format($creditoQuincenal, 2)"
                    color="teal"
                    icon="components.icons.calendar"
                    currency="true"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Tarjeta -->
                @php
                    $totalTarjeta = $totalTarjetaDebito + $totalTarjetaCredito;
                @endphp

                <x-kpi.kpi-card
                    title="Tarjeta"
                    :value="$totalTarjeta"
                    :subtitleHtml="'Débito: $' .
                        number_format($totalTarjetaDebito, 2) .
                        '<br>Crédito: $' .
                        number_format($totalTarjetaCredito, 2)"
                    color="indigo"
                    icon="components.icons.credit-card"
                    currency="true"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Transferencia/Efectivo -->
                @php
                    $totalTransferenciaEfectivo = $totalTransferencia + $totalEfectivo;
                @endphp

                <x-kpi.kpi-card
                    title="Transferencia/Efectivo"
                    :value="$totalTransferenciaEfectivo"
                    :subtitleHtml="'Transferencia: $' .
                        number_format($totalTransferencia, 2) .
                        '<br>Efectivo: $' .
                        number_format($totalEfectivo, 2)"
                    color="brown"
                    icon="components.icons.cash"
                    currency="true"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Total General -->
                @php
                    $totalGeneral =
                        $totalEfectivo +
                        $totalTransferencia +
                        $totalTarjetaDebito +
                        $totalTarjetaCredito +
                        $creditoSemanal +
                        $creditoQuincenal +
                        $totalMonederoImporte;
                @endphp

                <x-kpi.kpi-card
                    title="Total General"
                    :value="$totalGeneral"
                    subtitle="Resumen completo del día"
                    color="blue"
                    icon="components.icons.dolar"
                    currency="true"
                    colClass="col-xl-2 col-md-8 col-sm-12"
                />
            </div>
        </div>

        <!-- SECCIÓN 3: Tablas -->
        <div
            class="flex-grow-1 d-flex flex-column gap-4"
            style="min-height: 0;"
        >
            <!-- TABLA DE CLIENTES DE TIENDA -->
            <div class="d-flex flex-column pb-4">
                <div class="col-12">
                    <div
                        class="card border-0 p-4"
                        style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                    >

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="flex-column">
                                <h5 class="mb-0 text-gray-800">CORTE TIENDA {{ $tiendaActual->NomTienda ?? '' }}
                                </h5>
                                @if ($fechaActual)
                                    <h6
                                        class="fw-semibold text-muted m-0"
                                        style="font-size: 0.9rem;"
                                    >
                                        {{ \Carbon\Carbon::parse($fechaActual)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    </h6>
                                @endif
                            </div>
                            <!-- <div class="d-flex gap-2">
                                                                <x-dashboard-buttons-procesar
                                                                    :corteTienda="$cortesContadoOptimizado"
                                                                    :corteTiendaSolicitudes="$cortesSolicitudesOptimizado"
                                                                />
                                                            </div> -->
                        </div>
                        <div class="table-responsive content-table-sm">
                            <!-- Corte de tienda CONTADO -->
                            @foreach ($cortesContadoOptimizado as $corteTienda)
                                <div class="table-responsive content-table-sm mb-4">

                                    <!-- Encabezado del cliente -->
                                    <div class="d-flex align-items-center gap-3">

                                        <!-- Icono -->
                                        <div
                                            class="rounded p-2"
                                            style="background-color: rgba(30, 66, 159, 0.1); display: flex; justify-content: center; align-items: center"
                                        >
                                            <div
                                                style="color: #1e429f; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center">
                                                @include('components.icons.box')
                                            </div>
                                        </div>

                                        <!-- Contenido -->
                                        <div class="flex-grow-1">
                                            <!-- Línea 1: Nombre y Status -->
                                            <div class="d-flex align-items-center flex-wrap gap-3">
                                                <h6 class="fw-semibold mb-0">
                                                    {{ $corteTienda->Customer->NomClienteCloud ?? 'Cliente' }}
                                                </h6>
                                                @php
                                                    $oracleData = $corteTienda->OracleData ?? [];
                                                    $totalPedidos = count($oracleData);
                                                @endphp

                                                @if ($totalPedidos > 0)
                                                    @foreach ($oracleData as $sourceId => $oracleInfo)
                                                        @php
                                                            $statusClass = 'tags-green';
                                                            $statusText =
                                                                $oracleInfo->Source_Transaction_Number ?? null;

                                                            if (empty($statusText)) {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PEDIDO';
                                                            } elseif ($oracleInfo->STATUS == 'ERROR') {
                                                                $statusClass = 'tags-red';
                                                            }
                                                        @endphp

                                                        <span class="{{ $statusClass }} d-inline-flex align-items-center">
                                                            {{ $statusText }}
                                                            @if (!empty($oracleInfo->Source_Transaction_Number) && $oracleInfo->STATUS == 'ERROR')
                                                                <i class="fas fa-exclamation-circle ms-1"></i>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="tags-red d-inline-flex align-items-center">
                                                        SIN PEDIDO
                                                    </span>
                                                @endif
                                            </div>

                                            @foreach ($corteTienda->OracleData as $sourceId => $oracleInfo)
                                                @if (!empty($oracleInfo->MENSAJE_ERROR))
                                                    <div
                                                        class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                        style="font-size: 0.75rem; font-weight: 400;"
                                                    >
                                                        <div class="d-flex align-items-start gap-2">
                                                            <div>
                                                                <span style="font-weight: 500;">
                                                                    {{ $oracleInfo->Transaction_On }}
                                                                    @if (!empty($oracleInfo->Transaction_On))
                                                                        -
                                                                    @endif
                                                                    Mensaje:
                                                                </span>
                                                                {{ $oracleInfo->Source_Transaction_Number }}
                                                                {{ $oracleInfo->MENSAJE_ERROR }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Tabla de productos -->
                                    <table class="mt-2 table">
                                        <thead class="table-head">
                                            <tr>
                                                <th>Código</th>
                                                <th>Artículo</th>
                                                <th class="text-end">Cantidad</th>
                                                <th class="text-end">Precio</th>
                                                <th class="text-end">IVA</th>
                                                <th class="text-end">Importe</th>
                                                <th>Pedido</th>
                                                <th class="rounded-end text-center">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sumCantArticulo = 0;
                                                $sumImporte = 0;
                                            @endphp
                                            @foreach ($corteTienda->cortes as $detalleCorte)
                                                <tr>
                                                    <td>{{ $detalleCorte->CodArticulo }}</td>
                                                    <td
                                                        class="text-truncate"
                                                        style="max-width: 200px;"
                                                        title="{{ $detalleCorte->NomArticulo }}"
                                                    >
                                                        {{ $detalleCorte->NomArticulo }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>

                                                    <td>
                                                        @if (empty($detalleCorte->Source_Transaction_Identifier) && $detalleCorte->SolicitudCancelacion != null)
                                                            <span class="tags-red">SOLICITUD CANCELACIÓN</span>
                                                        @elseif(empty($detalleCorte->Source_Transaction_Identifier))
                                                            <span class="tags-red">SIN PEDIDO</span>
                                                        @else
                                                            <span
                                                                class="{{ $detalleCorte->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}"
                                                            >
                                                                {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            // Obtener el Source_Transaction_Identifier del detalle
                                                            $sourceId =
                                                                $detalleCorte->Source_Transaction_Identifier ?? null;

                                                            // Buscar el status en OracleData usando el sourceId
                                                            $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null;
                                                            $status = $oracleInfo->STATUS ?? null;
                                                            $mensajeError = $oracleInfo->MENSAJE_ERROR ?? null;
                                                            $solicitudCancelacion =
                                                                $detalleCorte->SolicitudCancelacion ?? null;

                                                            // Determinar el estado a mostrar
                                                            if (!empty($solicitudCancelacion)) {
                                                                $statusClass = 'tags-red';
                                                                // $statusText = 'CANCELACIÓN SOLICITADA';
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
                                                            } elseif (empty($status)) {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PROCESAR';
                                                            } else {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PROCESAR';
                                                            }
                                                        @endphp

                                                        <span
                                                            class="{{ $statusClass }} d-inline-flex align-items-center gap-1"
                                                        >
                                                            {{ $statusText }}
                                                        </span>

                                                        <!-- Mostrar mensaje de error si existe -->
                                                        @if (!empty($mensajeError) && $status == 'ERROR')
                                                            <button
                                                                class="btn btn-sm btn-outline-danger mt-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#mensajeError{{ $detalleCorte->IdCortesTienda }}"
                                                                title="Ver error"
                                                                style="font-size: 0.7rem; padding: 0.15rem 0.5rem;"
                                                            >
                                                                @include('components.icons.info') Ver error
                                                            </button>
                                                            @include('CortesTienda.ModalMensajeErrorOracle')
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                                    $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                                                @endphp
                                            @endforeach

                                            <!-- Monedero Electrónico -->
                                            @foreach ($totalMonedero as $monedero)
                                                @if ($corteTienda->Bill_To == $monedero->Bill_To)
                                                    <tr class="table-light">
                                                        <td
                                                            colspan="2"
                                                            class="fw-bold text-danger p-1 text-end"
                                                        >Dinero
                                                            Electrónico:</td>
                                                        <td
                                                            colspan="3"
                                                            class="p-1"
                                                        ></td>
                                                        <td class="fw-bold text-danger p-1 text-end">
                                                            ${{ number_format($monedero->importe, 2) }}</td>
                                                        <td
                                                            colspan="2"
                                                            class="p-1"
                                                        ></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr class="table-light">
                                                <td
                                                    colspan="2"
                                                    class="fw-bold p-1 text-end"
                                                >SubTotales:</td>
                                                <td class="fw-bold p-1 text-end">{{ number_format($sumCantArticulo, 3) }}
                                                </td>
                                                <td colspan="2 p-1"></td>
                                                <td class="fw-bold p-1 text-end">${{ number_format($sumImporte, 2) }}</td>
                                                <td colspan="2 p-1"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            <!-- Corte de tienda SOLICITUDES DE FACTURA -->
                            @foreach ($cortesSolicitudesOptimizado as $corteTienda)
                                <div class="table-responsive content-table-sm mb-4">

                                    <!-- Encabezdo del cliente -->
                                    <div class="d-flex align-items-center gap-3">

                                        <!-- Icono -->
                                        <div
                                            class="rounded p-2"
                                            style="background-color: rgba(30, 66, 159, 0.1); display: flex; justify-content: center; align-items: center"
                                        >
                                            <div
                                                style="color: #1e429f; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center">
                                                @include('components.icons.user')
                                            </div>
                                        </div>

                                        <!-- Contenido -->
                                        <div class="flex-grow-1">
                                            <!-- Línea 1: Nombre y Status -->
                                            <div class="d-flex align-items-center flex-wrap gap-3">
                                                <h6
                                                    class="fw-semibold @if (!is_null($corteTienda->Customer->Editar)) text-danger @endif mb-0">
                                                    {{ $corteTienda->Customer->NomCliente ?? ($corteTienda->Customer->NomClienteCloud ?? 'Cliente') }}
                                                    @if (!is_null($corteTienda->Customer->Editar))
                                                        <span class="tags-red ms-2">SIN LIGAR</span>
                                                    @endif
                                                </h6>

                                                @php
                                                    $oracleData = $corteTienda->OracleData ?? [];
                                                    $totalPedidos = count($oracleData);
                                                @endphp

                                                @if ($totalPedidos > 0)
                                                    @foreach ($oracleData as $sourceId => $oracleInfo)
                                                        @php
                                                            $statusClass = 'tags-green';
                                                            $statusText =
                                                                $oracleInfo->Source_Transaction_Number ?? null;

                                                            if (empty($statusText)) {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PEDIDO';
                                                            } elseif ($oracleInfo->STATUS == 'ERROR') {
                                                                $statusClass = 'tags-red';
                                                            }
                                                        @endphp

                                                        <span
                                                            class="{{ $statusClass }} d-inline-flex align-items-center">
                                                            {{ $statusText }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="tags-red d-inline-flex align-items-center">
                                                        SIN PEDIDO
                                                    </span>
                                                @endif
                                            </div>

                                            @foreach ($corteTienda->OracleData as $sourceId => $oracleInfo)
                                                @if (!empty($oracleInfo->MENSAJE_ERROR))
                                                    <div
                                                        class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                        style="font-size: 0.75rem; font-weight: 400;"
                                                    >
                                                        <div class="d-flex align-items-start gap-2">
                                                            <div>
                                                                <span style="font-weight: 500;">
                                                                    {{ $oracleInfo->Transaction_On }}
                                                                    @if (!empty($oracleInfo->Transaction_On))
                                                                        -
                                                                    @endif
                                                                    Mensaje:
                                                                </span>
                                                                {{ $oracleInfo->Source_Transaction_Number }}
                                                                {{ $oracleInfo->MENSAJE_ERROR }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Tabla de productos -->
                                    <table class="mt-2 table">
                                        <thead class="table-head">
                                            <tr>
                                                <th>Código</th>
                                                <th>Artículo</th>
                                                <th class="text-end">Cantidad</th>
                                                <th class="text-end">Precio</th>
                                                <th class="text-end">IVA</th>
                                                <th class="text-end">Importe</th>
                                                <th>Pedido</th>
                                                <th class="rounded-end text-center">Estatus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sumCantArticulo = 0;
                                                $sumImporte = 0;
                                            @endphp

                                            @foreach ($corteTienda->cortes as $detalleCorte)
                                                <tr>
                                                    <td>{{ $detalleCorte->CodArticulo }}</td>
                                                    <td
                                                        class="text-truncate"
                                                        style="max-width: 200px;"
                                                        title="{{ $detalleCorte->NomArticulo }}"
                                                    >
                                                        {{ $detalleCorte->NomArticulo }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ number_format($detalleCorte->CantArticulo, 4) }}
                                                    </td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->PrecioArticulo, 2) }}
                                                    </td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->IvaArticulo, 2) }}
                                                    </td>
                                                    <td class="text-end">
                                                        ${{ number_format($detalleCorte->ImporteArticulo, 2) }}
                                                    </td>

                                                    <!-- Columna Pedido -->
                                                    <td>
                                                        @php
                                                            $sourceId =
                                                                $detalleCorte->Source_Transaction_Identifier ?? null;
                                                        @endphp

                                                        @if (empty($sourceId) && $detalleCorte->SolicitudCancelacion != null)
                                                            <span class="tags-red">Solicitud Cancelación</span>
                                                        @elseif(empty($sourceId))
                                                            <span class="tags-red">SIN PEDIDO</span>
                                                        @else
                                                            <span
                                                                class="{{ $oracleInfo->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}"
                                                            >
                                                                {{ substr_replace($sourceId, '_', 3, 0) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <!-- Columna Status -->
                                                    <td class="text-center">
                                                        @php
                                                            // Obtener el Source_Transaction_Identifier del detalle
                                                            $sourceId =
                                                                $detalleCorte->Source_Transaction_Identifier ?? null;

                                                            // Buscar el status en OracleData usando el sourceId
                                                            $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null;
                                                            $status = $oracleInfo->STATUS ?? null;
                                                            $mensajeError = $oracleInfo->MENSAJE_ERROR ?? null;
                                                            $solicitudCancelacion =
                                                                $detalleCorte->SolicitudCancelacion ?? null;

                                                            // Determinar el estado a mostrar
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
                                                            } elseif (empty($status)) {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PROCESAR';
                                                            } else {
                                                                $statusClass = 'tags-red';
                                                                $statusText = 'SIN PROCESAR';
                                                            }
                                                        @endphp

                                                        <span
                                                            class="{{ $statusClass }} d-inline-flex align-items-center gap-1"
                                                        >
                                                            {{ $statusText }}
                                                        </span>
                                                    </td>
                                                </tr>

                                                @php
                                                    $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                                    $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                                                @endphp
                                            @endforeach

                                            <!-- Totales -->
                                            <tr class="table-light">
                                                <td
                                                    colspan="2"
                                                    class="fw-bold p-1 text-end"
                                                >SubTotales:</td>
                                                <td class="fw-bold p-1 text-end">{{ number_format($sumCantArticulo, 3) }}
                                                </td>
                                                <td
                                                    colspan="2"
                                                    class="p-1"
                                                ></td>
                                                <td class="fw-bold p-1 text-end">${{ number_format($sumImporte, 2) }}</td>
                                                <td
                                                    colspan="2"
                                                    class="p-1"
                                                ></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            <!-- Mensaje cuando no se encuentran datos -->
                            @if (
                                (empty($cortesContadoOptimizado) || count($cortesContadoOptimizado) == 0) &&
                                    (empty($cortesSolicitudesOptimizado) || count($cortesSolicitudesOptimizado) == 0))
                                <table class="mt-2 table">
                                    <thead class="table-head">
                                        <tr>
                                            <th>Código</th>
                                            <th>Artículo</th>
                                            <th class="text-end">Cantidad</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">IVA</th>
                                            <th class="text-end">Importe</th>
                                            <th>Pedido</th>
                                            <th class="rounded-end text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                colspan="12"
                                                class="py-5 text-center"
                                            >
                                                <x-table-empty-state
                                                    title="No hay ventas para mostrar"
                                                    icon="ticket"
                                                    :message="'No se encontraron ventas de contado en el período seleccionado.'"
                                                    :suggestion="'Prueba cambiando las fechas o los filtros de búsqueda para ver más resultados.'"
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-layout.page-container>
@endsection
