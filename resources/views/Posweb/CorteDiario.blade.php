@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Corte Diario de Tienda')
@section('dashboardWidth', 'width-general')
<style>
    table {
        font-size: 13px;
    }

    #totales {
        color: red;
    }

    #sumatorias {
        text-align: right
    }
</style>
@section('contenido')

    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Corte Diario - ' . $tienda->NomTienda])
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" id="formCorte" action="/CorteDiario">
            <div class="input-group" style="max-width: 300px">
                <input class="form-control" name="fecha" id="fecha" type="date" value="{{ $fecha }}">
            </div>
            <a href="/GenerarCortePDF/{{ $fecha }}/{{ $tienda->IdTienda }}/{{ $idDatCaja }}" target="_blank"
                type="button" class="btn btn-sm btn-dark">
                <span class="material-icons text-white">print</span>
            </a>
        </form>

        <!--CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            @foreach ($cortesTienda as $corteTienda)
                @foreach ($corteTienda->Customer as $customer)
                    <div class="d-flex justify-content-left">
                        <h6 class="p-1 bg-dark text-white rounded-3">{{ $customer->NomClienteCloud }}</h6>
                    </div>
                @endforeach
                <table>
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

                <table>
                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS QUINCENALES-->
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 4)
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
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 3)
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
            @endforeach
        </div>
        <!--TERMINA CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->


        <!--SOLICITUDES DE FACTURA-->
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            @foreach ($facturas as $factura)
                <div class="d-flex justify-content-left">
                    <h6 class="p-1 bg-dark text-white rounded-3">{{ $factura->NomCliente }}</h6>
                </div>
                <table>
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
                        @foreach ($factura->Factura as $detalleFactura)
                            <tr>
                                <td style="width: 10vh">{{ $detalleFactura->CodArticulo }}</td>
                                <td style="width: 60vh">{{ $detalleFactura->NomArticulo }}</td>
                                <td style="width: 15vh">{{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
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
                <table>
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
            @endforeach
        </div>
        <!--TERMINA SOLICITUDES DE FACTURA-->

        <!--SUMATORIAS FINALES-->
        <div class="content-table content-table-full">
            <div class="d-flex justify-content-end">
                <div class="col-auto">
                    <table id="sumatorias" style="font-size: 18px" class="table">
                        <thead>
                            <tr>
                                <td>Dinero Electrónico Crédito Quincenal: </td>
                                <td>${{ number_format($totalMonederoQuincenal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Dinero Electrónico Crédito Semanal: </td>
                                <td>${{ number_format($totalMonederoSemanal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Dinero Electrónico: </td>
                                <td style="font-weight: bold; color: red;">
                                    ${{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) }}</td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <td>Crédito Quincenal: </td>
                                <td>${{ number_format($creditoQuincenal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Crédito Semanal: </td>
                                <td>${{ number_format($creditoSemanal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Créditos: </td>
                                <td style="font-weight: bold; color: red;">
                                    ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                                </td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <td>Tarjeta Débito: </td>
                                <td>${{ number_format($totalTarjetaDebito, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Tarjeta Crédito: </td>
                                <td>${{ number_format($totalTarjetaCredito, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Tarjeta: </td>
                                <td style="font-weight: bold; color: red;">
                                    ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                                </td>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <td>Total Transferencia: </td>
                                <td style="font-weight: bold; color: red;">${{ number_format($totalTransferencia, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Factura: </td>
                                <td style="font-weight: bold; color: red;">${{ number_format($totalFactura, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Efectivo: </td>
                                <td style="font-weight: bold; color: red;">${{ number_format($totalEfectivo, 2) }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--TERMINA SUMATORIAS FINALES-->

    </div>

    <script>
        const fecha = document.getElementById('fecha');
        const formCorte = document.getElementById('formCorte');

        fecha.addEventListener('change', function() {
            formCorte.submit();
        });
    </script>
@endsection
