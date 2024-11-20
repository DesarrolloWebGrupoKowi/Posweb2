<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        caption {
            font-weight: medium;
            font-size: 11px;
        }

        table {
            /* margin-top: 8px; */
            margin-bottom: 32px;
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
            line-height: 18px;
        }

        th {
            text-align: left;
            /* background-color: #000000;
            color: white;
            font-size: 12px; */
        }

        .tbl-corte tr:nth-child(even),
        .totales {
            /* background-color: #dddddd; */
            /* border-bottom: 1px solid black; */
        }

        #DivSumatorias {
            /* float: right; */
        }

        #sumatorias {
            font-size: 11px;
            width: 100%;
        }

        .mayus {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    {{-- Ventas normales --}}
    <div>
        {{-- <caption><i>{{ $titulo }}</i></caption> --}}
        <caption>{{ $nomTienda }}</caption>
        <caption>{{ $direccion }}</caption>
        <caption>{{ $RFC }} / {{ $telefono }}</caption>

        <br>

        <caption>Dia {{ $fecha }}</caption>
        <caption>En caja {{ $numCaja == 0 ? 'TODAS' : $numCaja }}</caption>

        <br>

        <caption style="font-size: 18px; font-weight: bold;">{{ $titulo }} </caption>

        {{-- <hr> --}}
        <br>
        <br>

        @foreach ($cortesTienda as $corteTienda)
            @foreach ($corteTienda->Customer as $customer)
            @endforeach
            <table>
                <caption style="text-align: left;">{{ $customer->NomClienteCloud }}</caption>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th style="text-align: center;">Precio</th>
                        <th style="text-align: center;">Iva</th>
                        <th style="text-align: center;">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($corteTienda->CorteTienda as $detalleCorte)
                        <tr class="striped">
                            <td>{{ $detalleCorte->CodArticulo }}</td>
                            <td>{{ $detalleCorte->NomArticulo }}</td>
                            <td>{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 40px;">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</span>
                            </td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 40px;">{{ number_format($detalleCorte->IvaArticulo, 2) }}</span>
                            </td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 100px;">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</span>
                            </td>
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                            $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach ($totalMonedero as $monedero)
                        @if ($corteTienda->Bill_To == $monedero->Bill_To)
                            <tr>
                                <td colspan="2" style="font-weight: bold; color: red">Dinero Electrónico: </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-weight: bold; text-align: right; color: red"><span
                                        style="display: inline-block">$</span> <span
                                        style="display: inline-block; min-width: 40px;">{{ number_format($monedero->importe, 2) }}</span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="totales">
                        <td colspan="2" style="font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;font-weight: bold;"><span style="display: inline-block">$</span>
                            <span
                                style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">{{ number_format($sumImporte, 2) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endforeach
    </div>

    {{-- Solicitudes de factura --}}
    <div style="page-break-after: always;">
        @foreach ($facturas as $factura)
            <table id="tbl-corte">
                <caption style="text-align: left">{{ $factura->NomCliente }}</caption>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th style="text-align: center;">Precio</th>
                        <th style="text-align: center;">Iva</th>
                        <th style="text-align: center;">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($factura->FacturaCorteDiario as $detalleFactura)
                        <tr class="striped">
                            <td>{{ $detalleFactura->CodArticulo }}</td>
                            <td>{{ $detalleFactura->NomArticulo }}</td>
                            <td>{{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}</td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 40px;">{{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}</span>
                            </td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 40px;">{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</span>
                            </td>
                            <td style="text-align: right;"><span style="display: inline-block">$</span> <span
                                    style="display: inline-block; min-width: 100px;">{{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}</span>
                            </td>
                            {{-- <td>{{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</td>
                            <td>{{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}</td> --}}
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                            $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totales">
                        <td colspan="2" style="font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;font-weight: bold;"><span style="display: inline-block">$</span>
                            <span
                                style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">{{ number_format($sumImporte, 2) }}</span>
                        </td>
                        {{-- <td style="font-weight: bold;">{{ number_format($sumImporte, 2) }}</td> --}}
                    </tr>
                </tfoot>
            </table>
        @endforeach
    </div>

    <!--SUMATORIAS FINALES-->
    <div id="">
        <table style="margin-bottom: 12px">
            <tbody>
                @php
                    $totalImporte = 0;
                @endphp
                @foreach ($totalMonedero as $monedero)
                    <tr>
                        <td style="text-align: left; width: 80%;">
                            {{ ucfirst(strtolower($monedero->NomClienteCloud)) }} </td>
                        <td style="text-align: right;">
                            <span style="display: inline-block">$</span>
                            <span style="display: inline-block; min-width: 100px;">
                                {{ number_format($monedero->importe, 2) }}
                            </span>
                        </td>
                    </tr>
                    @php
                        $totalImporte += $monedero->importe;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-weight: bold;">Total Dinero Electrónico: </td>
                    <td style="font-weight: bold; text-align: right; ">
                        <span style="display: inline-block">$</span>
                        <span style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">
                            {{ number_format($totalImporte, 2) }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
        <table style="margin-bottom: 12px">
            <tbody>
                <tr>
                    <td style="text-align: left; width: 80%;">Crédito Quincenal: </td>
                    <td style="text-align: right">${{ number_format($creditoQuincenal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 80%;">Crédito Semanal: </td>
                    <td style="text-align: right">${{ number_format($creditoSemanal, 2) }}</td>
                </tr>
                <tr class="totales">
                    <td style="text-align: left; font-weight: bold; width: 80%;">Total Créditos: </td>
                    <td style="text-align: right; font-weight: bold;">
                        <span style="display: inline-block">$</span>
                        <span style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">
                            {{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="margin-bottom: 12px">
            <tbody>
                <tr>
                    <td style="text-align: left; width: 80%;">Tarjeta Débito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaDebito, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 80%;">Tarjeta Crédito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaCredito, 2) }}</td>
                </tr>
                <tr class="totales">
                    <td style="text-align: left; font-weight: bold; width: 80%;">Total Tarjeta: </td>
                    <td style="text-align: right; font-weight: bold;">
                        <span style="display: inline-block">$</span>
                        <span style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">
                            {{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="margin-bottom: 12px">
            <tbody>
                <tr>
                    <td style="text-align: left; width: 80%;">Total Transferencia: </td>
                    <td style="text-align: right">
                        ${{ number_format($totalTransferencia, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 80%;">Total Factura: </td>
                    <td style="text-align: right">${{ number_format($totalFactura, 2) }}
                    </td>
                </tr>
                <tr class="totales">
                    <td style="text-align: left; font-weight: bold; width: 80%;">Total Efectivo: </td>
                    <td style="text-align:right; font-weight: bold;">
                        <span style="display: inline-block">$</span>
                        <span style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">
                            {{ number_format($totalEfectivo, 2) }}
                        </span>
                    </td>
                </tr>
                <tr class="totales">
                    <td style="text-align: left; font-weight: bold; width: 80%;">Total General: </td>
                    <td style="text-align:right; font-weight: bold;">
                        <span style="display: inline-block">$</span>
                        <span style="display: inline-block; min-width: 100px; border-bottom: 1px solid black;">
                            {{-- {{ number_format($totalEfectivo, 2) }} --}}
                            {{ number_format($totalEfectivo + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalImporte, 2) }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
