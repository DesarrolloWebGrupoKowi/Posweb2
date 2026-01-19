@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Corte por Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <!--CORTE DIARIO DE TIENDA-->
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">
        <!-- HEADER CON TÍTULO Y BOTONES DE PROCESAMIENTO -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white;">
            <div class="row mb-4 gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Cortes por Tiendas'])
                </div>

                <!-- Filtro de Fecha -->
                <x-dashboard-filters :tiendas="$tiendas"
                    :showReporte="true"
                    :showTodasTiendas="true"
                    :tiendaSeleccionada="request()->get('tienda_id')"
                    :reporteSeleccionado="request()->get('idReporte')" />


                <x-dashboard-notificacion-procesar-tienda :tiendaActual="$tiendaActual" />

            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-gray-800">{{ $nomTienda }} - CAJA
                    {{ $idCaja == 0 ? 'TODAS' : $numCaja }}</h5>

                <x-dashboard-buttons-procesar :corteTienda="$cortesTienda"
                    :corteTiendaSolicitudes="$facturas" />
            </div>

            <!-- KPIs DE FORMAS DE PAGO -->
            <div class="row g-4 mb-4">
                <!-- Dinero Electrónico -->
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Dinero Electrónico</h6>
                                    @php
                                        $totalImporte = 0;
                                    @endphp
                                    @foreach ($totalMonedero as $monedero)
                                        @php
                                            $totalImporte += $monedero->importe;
                                        @endphp
                                    @endforeach
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($totalImporte, 2) }}
                                    </h3>
                                    <small class="d-block mt-1 text-muted">
                                        {{ count($totalMonedero) }} cliente(s)
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(124, 58, 237, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #7c3aed;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.credit-card')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crédito -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Crédito</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                                    </h3>
                                    <small class="d-block mt-1 text-muted">
                                        Semanal: ${{ number_format($creditoSemanal, 2) }}<br>
                                        Quincenal: ${{ number_format($creditoQuincenal, 2) }}
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(3, 84, 63, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #03543f;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.calendar')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Tarjeta</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                                    </h3>
                                    <small class="d-block mt-1 text-muted">
                                        Débito: ${{ number_format($totalTarjetaDebito, 2) }}<br>
                                        Crédito: ${{ number_format($totalTarjetaCredito, 2) }}
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(30, 66, 159, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #1e429f;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.credit-card')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transferencia/Efectivo -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Transferencia/Efectivo</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($totalTransferencia + $totalEfectivo, 2) }}
                                    </h3>
                                    <small class="d-block mt-1 text-muted">
                                        Transferencia: ${{ number_format($totalTransferencia, 2) }}<br>
                                        Efectivo: ${{ number_format($totalEfectivo, 2) }}
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(114, 59, 19, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #723b13;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.cash')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total General -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Total General</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($totalEfectivo + $totalTransferencia + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalImporte, 2) }}
                                    </h3>
                                    <small class="d-block mt-1 text-muted">
                                        Resumen completo del día
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(9, 109, 217, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #096dd9;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.dolar')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLA DE CLIENTES DE TIENDA -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    {{-- <h5 class="mb-3 text-gray-800">CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)</h5> --}}

                    @if (count($cortesTienda) == 0)
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="mx-auto mb-3 empty-state-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-100 w-100"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h5 class="text-gray-500 mb-2">No hay clientes registrados</h5>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive content-table-sm">
                            @foreach ($cortesTienda as $corteTienda)
                                <div class="mb-4">
                                    <!-- Encabezado del cliente -->
                                    {{-- <div
                                        class="d-flex justify-content-between align-items-center mb-2 p-3 bg-light rounded"> --}}
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 rounded"
                                            style="background-color: rgba(30, 66, 159, 0.1); display: flex; justify-content: center; align-items: center">
                                            <div
                                                style="color: #1e429f; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center">
                                                @include('components.icons.box')
                                            </div>
                                        </div>
                                        <h6 class="mb-0">
                                            {{ $corteTienda->Customer[0]->NomClienteCloud ?? 'Cliente' }}</h6>
                                        <div class="d-flex gap-2">
                                            @foreach ($corteTienda->PedidoOracle as $pedidoOracle)
                                                <span
                                                    class="{{ empty($pedidoOracle->Source_Transaction_Identifier) ? 'tags-red' : ($pedidoOracle->STATUS == 'ERROR' ? 'tags-red' : 'tags-green') }}">
                                                    @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                                        SIN PEDIDO
                                                    @else
                                                        {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                                                        @if ($pedidoOracle->STATUS == 'ERROR')
                                                            <i class="fas fa-exclamation-circle ms-1"></i>
                                                        @endif
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- </div> --}}

                                    <!-- Tabla de productos -->
                                    <table class="table">
                                        <thead class="table-head">
                                            <tr>
                                                <th class="rounded-start">Código</th>
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
                                            @php
                                                $sumCantArticulo = 0;
                                                $sumImporte = 0;
                                            @endphp
                                            @foreach ($corteTienda->CorteTiendaOracle as $detalleCorte)
                                                <tr>
                                                    <td>{{ $detalleCorte->CodArticulo }}</td>
                                                    <td class="text-truncate"
                                                        style="max-width: 200px;"
                                                        title="{{ $detalleCorte->NomArticulo }}">
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
                                                            <span class="tags-red">Solicitud Cancelación</span>
                                                        @elseif(empty($detalleCorte->Source_Transaction_Identifier))
                                                            <span class="tags-red">SIN PEDIDO</span>
                                                        @else
                                                            <span
                                                                class="{{ $detalleCorte->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                                                {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if (
                                                            (empty($detalleCorte->STATUS) || $detalleCorte->STATUS == 'NULL') &&
                                                                empty($detalleCorte->MENSAJE_ERROR) &&
                                                                empty($detalleCorte->Batch_Name))
                                                            <span class="tags-red">SIN PROCESAR</span>
                                                        @elseif($detalleCorte->STATUS == 'ERROR')
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#mensajeError{{ $detalleCorte->IdCortesTienda }}"
                                                                title="Ver error">
                                                                @include('components.icons.info') VER ERROR
                                                            </button>
                                                            @include('CortesTienda.ModalMensajeErrorOracle')
                                                        @elseif($detalleCorte->STATUS == 'PROCESADO' || $detalleCorte->STATUS == 'EN PROCESO')
                                                            <span
                                                                class="{{ $detalleCorte->STATUS == 'PROCESADO' ? 'tags-green' : 'tags-yellow' }}">
                                                                {{ $detalleCorte->STATUS }}
                                                            </span>
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
                                                        <td colspan="2"
                                                            class="text-end fw-bold text-danger">Dinero
                                                            Electrónico:</td>
                                                        <td colspan="3"></td>
                                                        <td class="text-end fw-bold text-danger">
                                                            ${{ number_format($monedero->importe, 2) }}</td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr class="table-light">
                                                <td colspan="2"
                                                    class="text-end fw-bold">SubTotales:</td>
                                                <td class="text-end fw-bold">{{ number_format($sumCantArticulo, 3) }}</td>
                                                <td colspan="2"></td>
                                                <td class="text-end fw-bold">${{ number_format($sumImporte, 2) }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SOLICITUDES DE FACTURA -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    {{-- <h5 class="mb-3 text-gray-800">SOLICITUDES DE FACTURA</h5> --}}

                    @if (count($facturas) == 0 || !collect($facturas)->some(fn($f) => count($f->Factura) !== 0))
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="mx-auto mb-3 empty-state-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-100 w-100"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h5 class="text-gray-500 mb-2">No hay solicitudes de factura</h5>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive content-table-sm">
                            @foreach ($facturas as $factura)
                                @if (count($factura->Factura) !== 0)
                                    <div class="mb-4">
                                        <!-- Encabezado del cliente -->
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 rounded"
                                                style="background-color: rgba(124, 58, 237, 0.1);display: flex; justify-content: center">
                                                <div
                                                    style="color: #7c3aed; width: 20px; height: 20px; display: flex; justify-content: center">
                                                    @include('components.icons.user')
                                                </div>
                                            </div>
                                            <div>
                                                @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                                                    <h6 class="mb-0 text-white bg-danger tags-red">
                                                        FALTA LIGAR CLIENTE - {{ $factura->NomCliente }}
                                                    </h6>
                                                @else
                                                    <h6 class="mb-0">{{ $factura->NomCliente }}</h6>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                @foreach ($factura->PedidoOracle as $pedidoOracle)
                                                    <span
                                                        class="{{ empty($pedidoOracle->Source_Transaction_Identifier) ? 'tags-red' : ($pedidoOracle->STATUS == 'ERROR' ? 'tags-red' : 'tags-green') }}">
                                                        @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                                            SIN PEDIDO
                                                        @else
                                                            {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                                                            @if ($pedidoOracle->STATUS == 'ERROR')
                                                                <i class="fas fa-exclamation-circle ms-1"></i>
                                                            @endif
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Tabla de productos -->
                                        <table class="table">
                                            <thead class="table-head">
                                                <tr>
                                                    <th class="rounded-start">Código</th>
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
                                                @php
                                                    $sumCantArticulo = 0;
                                                    $sumImporte = 0;
                                                @endphp
                                                @foreach ($factura->Factura as $detalleFactura)
                                                    <tr>
                                                        <td>{{ $detalleFactura->CodArticulo }}</td>
                                                        <td class="text-truncate"
                                                            style="max-width: 200px;"
                                                            title="{{ $detalleFactura->NomArticulo }}">
                                                            {{ $detalleFactura->NomArticulo }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
                                                        </td>
                                                        <td class="text-end">
                                                            ${{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}
                                                        </td>
                                                        <td class="text-end">
                                                            ${{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}
                                                        </td>
                                                        <td class="text-end">
                                                            ${{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}
                                                        </td>

                                                        <td>
                                                            @if (empty($detalleFactura->Source_Transaction_Identifier))
                                                                <span class="tags-red">SIN PEDIDO</span>
                                                            @else
                                                                <span
                                                                    class="{{ $detalleFactura->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                                                    {{ substr_replace($detalleFactura->Source_Transaction_Identifier, '_', 3, 0) }}
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="text-center">
                                                            @if (
                                                                (empty($detalleFactura->STATUS) || $detalleFactura->STATUS == 'NULL') &&
                                                                    empty($detalleFactura->MENSAJE_ERROR) &&
                                                                    empty($detalleFactura->Batch_Name))
                                                                <span class="tags-red">SIN PROCESAR</span>
                                                            @elseif($detalleFactura->STATUS == 'ERROR')
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#mensajeError{{ $detalleFactura->IdCortesTienda }}"
                                                                    title="Ver error">
                                                                    @include('components.icons.info') VER ERROR
                                                                </button>
                                                                @include('CortesTienda.ModalMensajeErrorOracle')
                                                            @elseif($detalleFactura->STATUS == 'PROCESADO' || $detalleFactura->STATUS == 'EN PROCESO')
                                                                <span
                                                                    class="{{ $detalleFactura->STATUS == 'PROCESADO' ? 'tags-green' : 'tags-yellow' }}">
                                                                    {{ $detalleFactura->STATUS }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $sumCantArticulo =
                                                            $sumCantArticulo +
                                                            $detalleFactura->PivotDetalle->CantArticulo;
                                                        $sumImporte =
                                                            $sumImporte +
                                                            $detalleFactura->PivotDetalle->ImporteArticulo;
                                                    @endphp
                                                @endforeach
                                                <tr class="table-light">
                                                    <td colspan="2"
                                                        class="text-end fw-bold">SubTotales:</td>
                                                    <td class="text-end fw-bold">{{ number_format($sumCantArticulo, 3) }}
                                                    </td>
                                                    <td colspan="2"></td>
                                                    <td class="text-end fw-bold">${{ number_format($sumImporte, 2) }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .tags-yellow {
            background-color: rgba(245, 158, 11, 0.1);
            color: #b45309;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .tags-red {
            background-color: rgba(190, 24, 93, 0.1);
            color: #be185d;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .tags-blue {
            background-color: rgba(30, 66, 159, 0.1);
            color: #1e429f;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .tags-green {
            background-color: rgba(3, 84, 63, 0.1);
            color: #03543f;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            color: #d1d5db;
        }

        .table-head {
            /* background-color: #f8fafc; */
            /* color: #334155; */
            /* font-weight: 600; */
            font-size: 0.8rem;
        }

        .table th {
            border-top: none;
            font-size: 0.875rem;
            padding: 0.65rem 1rem;
        }

        .table td {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            vertical-align: middle;
        }

        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
@endsection
