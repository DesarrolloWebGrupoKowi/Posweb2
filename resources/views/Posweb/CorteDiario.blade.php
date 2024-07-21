@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Corte Diario de Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')

    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Corte Diario - ' . $tienda->NomTienda])
                <div class="d-flex align-items-center justify-content-end gap-4">
                    <form class="d-flex align-items-center justify-content-end gap-2" action="/CorteDiario">
                        <div class="input-group" style="min-width: 200px">
                            <input class="form-control rounded" style="line-height: 18px" name="fecha" id="fecha"
                                type="date" value="{{ $fecha }}" autofocus>
                        </div>
                        <input type="submit" class="d-none">
                    </form>
                    <a href="/GenerarCortePDF/{{ $fecha }}/{{ $tienda->IdTienda }}/{{ $idDatCaja }}"
                        class="btn btn-dark-outline">
                        @include('components.icons.print')
                    </a>
                </div>
            </div>
        </div>

        <!--SUMATORIAS FINALES-->
        <div class="row">
            {{-- Dinero Electrónico --}}
            <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <h6 class="text-dark">Dinero Electrónico</h6>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Crédito Quincenal: </span>
                        <span>${{ number_format($totalMonederoQuincenal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Crédito Semanal: </span>
                        <span>${{ number_format($totalMonederoSemanal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total: </span>
                        <b class="{{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) }}
                        </b>
                    </div>
                </div>
            </div>
            {{-- Crédito  --}}
            <div class="col-12 col-md-6 col-lg-3 mb-4 mb-md-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <h6 class="text-dark">Crédito</h6>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Crédito Quincenal: </span>
                        <span>${{ number_format($creditoQuincenal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Crédito Semanal: </span>
                        <span>${{ number_format($creditoSemanal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Créditos: </span>
                        <b class="{{ number_format($creditoSemanal + $creditoQuincenal, 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                        </b>
                    </div>
                </div>
            </div>
            {{-- Tarjeta  --}}
            <div class="col-12 col-md-6 col-lg-3 mb-4 mb-md-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <h6 class="text-dark">Tarjeta</h6>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Tarjeta Débito: </span>
                        <span>${{ number_format($totalTarjetaDebito, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Tarjeta Crédito: </span>
                        <span>${{ number_format($totalTarjetaCredito, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Tarjeta: </span>
                        <b class="{{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                        </b>
                    </div>
                </div>
            </div>
            {{-- Totales --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <h6 class="text-dark">Totales</h6>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Transferencia: </span>
                        <span>${{ number_format($totalTransferencia, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Factura: </span>
                        <span>${{ number_format($totalFactura, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Efectivo: </span>
                        <b class="{{ number_format($totalEfectivo, 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totalEfectivo, 2) }}
                        </b>
                    </div>
                </div>
            </div>
        </div>
        <!--TERMINA SUMATORIAS FINALES-->

        <!--CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
        @foreach ($cortesTienda as $corteTienda)
            <div class="content-table content-table-flex-none content-table-full card border-0 p-4"
                style="border-radius: 10px">
                @foreach ($corteTienda->Customer as $customer)
                    <div class="d-flex justify-content-between">
                        <h6 class="">{{ $customer->NomClienteCloud }}</h6>
                        <div class="d-flex gap-4">
                            <span class="d-flex">
                                <p>Cliente: </p>
                                <h6 class="ps-1">{{ $corteTienda->IdClienteCloud }}</h6>
                            </span>
                            <span class="d-flex">
                                <p>Bill to: </p>
                                <h6 class="ps-1">{{ $corteTienda->Bill_To }}</h6>
                            </span>
                        </div>
                    </div>
                @endforeach
                <table style="font-size: 13px">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th class="rounded-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody class="cuchi">
                        @php
                            $sumCantArticulo = 0;
                            $sumImporte = 0;
                        @endphp
                        @foreach ($corteTienda->CorteTienda as $detalleCorte)
                            <tr>
                                <td style="width: 10vh">{{ $detalleCorte->CodArticulo }}</td>
                                <td style="width: 60vh">{{ $detalleCorte->NomArticulo }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                <td>{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                            </tr>
                            @php
                                $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                            @endphp
                        @endforeach
                    </tbody>
                </table>

                <table style="font-size: 13px">
                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS QUINCENALES-->
                    @if ($corteTienda->IdTipoNomina == 4)
                        <tr>
                            <th style="width: 10vh"></th>
                            <th style="width: 60vh; text-align:center">
                                <h6 style="color: red">Dinero Electrónico :</h6>
                            </th>
                            <th style="width: 15vh">
                                <h6></h6>
                            </th>
                            <th style="width: 15vh"></th>
                            <th></th>
                            <th style="width: 15vh; color: red;">
                                <h6>$ {{ number_format($totalMonederoQuincenal, 2) }}</h6>
                            </th>
                        </tr>
                    @endif
                    <!--TERMINA MONEDERO ELECTRONICO QUINCENALES-->

                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS SEMANALES-->
                    @if ($corteTienda->IdTipoNomina == 3)
                        <tr>
                            <th style="width: 10vh"></th>
                            <th style="width: 60vh; text-align:center">
                                <h6 style="color: red">Dinero Electrónico :</h6>
                            </th>
                            <th style="width: 15vh">
                                <h6></h6>
                            </th>
                            <th style="width: 15vh"></th>
                            <th></th>
                            <th style="width: 15vh; color: red;">
                                <h6>$ {{ number_format($totalMonederoSemanal, 2) }}</h6>
                            </th>
                        </tr>
                    @endif
                    <!--TERMINA MONEDERO ELECTRONICO SEMANALES-->
                    <tr>
                        <th style="width: 10vh"></th>
                        <th style="width: 60vh; text-align:center">
                            <h6>SubTotales :</h6>
                        </th>
                        <th style="width: 15vh">
                            <h6>{{ number_format($sumCantArticulo, 3) }}</h6>
                        </th>
                        <th style="width: 15vh"></th>
                        <th></th>
                        <th style="width: 15vh">
                            <h6>$ {{ number_format($sumImporte, 2) }}</h6>
                        </th>
                    </tr>
                </table>
            </div>
        @endforeach
        <!--TERMINA CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->


        <!--SOLICITUDES DE FACTURA-->
        @foreach ($facturas as $factura)
            @if (count($factura->FacturaLocal) != 0)
                <div class="content-table  content-table-flex-none content-table-full card border-0 p-4"
                    style="border-radius: 10px">
                    <div class="d-flex justify-content-left">
                        <h6 class="p-1 bg-dark text-white rounded-3">{{ $factura->NomCliente }}</h6>
                    </div>
                    <table style="font-size: 13px">
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Código</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Iva</th>
                                <th class="rounded-end">Importe</th>
                            </tr>
                        </thead>
                        <tbody class="cuchi">
                            @php
                                $sumCantArticulo = 0;
                                $sumImporte = 0;
                            @endphp
                            @foreach ($factura->FacturaLocal as $detalleFactura)
                                <tr>
                                    <td style="width: 10vh">{{ $detalleFactura->CodArticulo }}</td>
                                    <td style="width: 60vh">{{ $detalleFactura->NomArticulo }}</td>
                                    <td style="width: 15vh">
                                        {{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
                                    </td>
                                    <td style="width: 15vh">
                                        {{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}
                                    </td>
                                    <td>{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</td>
                                    <td style="width: 15vh">
                                        {{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}
                                    </td>
                                </tr>
                                @php
                                    $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                                    $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <table style="font-size: 13px">
                        <tr>
                            <th style="width: 10vh"></th>
                            <th style="width: 60vh; text-align:center">
                                <h6>SubTotales :</h6>
                            </th>
                            <th style="width: 15vh">
                                <h6>{{ number_format($sumCantArticulo, 3) }}</h6>
                            </th>
                            <th style="width: 15vh"></th>
                            <th></th>
                            <th style="width: 15vh">
                                <h6>$ {{ number_format($sumImporte, 2) }}</h6>
                            </th>
                        </tr>
                    </table>
                </div>
            @endif
        @endforeach
        <!--TERMINA SOLICITUDES DE FACTURA-->

    </div>

    <script>
        const fecha = document.getElementById('fecha');
        const formCorte = document.getElementById('formCorte');

        fecha.addEventListener('change', function() {
            formCorte.submit();
        });
    </script>
@endsection
