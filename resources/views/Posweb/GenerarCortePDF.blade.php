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
            font-weight: bold;
            font-size: 14px;
        }

        table {
            margin-top: 8px;
            margin-bottom: 32px;
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
            line-height: 18px;
        }

        th {
            text-align: left;
            background-color: #000000;
            color: white;
            font-size: 12px;
        }

        .tbl-corte tr:nth-child(even),
        .totales {
            background-color: #dddddd;
        }

        #DivSumatorias {
            float: right;
        }

        #sumatorias {
            font-size: 11px;
            width: 100%;
        }
    </style>
</head>

<body>
    {{-- Ventas normales --}}
    <div>
        <caption>{{ $titulo }}</caption>
        <caption>{{ $nomTienda }}</caption>
        <caption>CAJA: {{ $numCaja == 0 ? 'TODAS' : $numCaja }}</caption>
        <caption style="text-align: right">{{ $fecha }}</caption>

        @foreach ($cortesTienda as $corteTienda)
            @foreach ($corteTienda->Customer as $customer)
            @endforeach
            <table class="tbl-corte">
                <caption style="text-align: left">{{ $customer->NomClienteCloud }}</caption>
                <thead>
                    <tr class="cab">
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
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
                            <td>{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                            <td>{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
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
                                <td></td>
                                <td style="text-align: right; font-weight: bold; color: red">Dinero Electrónico: </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="font-weight: bold; color: red">{{ number_format($monedero->importe, 2) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="totales">
                        <td></td>
                        <td style="text-align: right; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">{{ number_format($sumImporte, 2) }}</td>
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
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
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
                            <td>{{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</td>
                            <td>{{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}</td>
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                            $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totales">
                        <td></td>
                        <td style="text-align: right; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">{{ number_format($sumImporte, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endforeach
    </div>

    <!--SUMATORIAS FINALES-->
    <div id="DivSumatorias">
        <table>
            <tbody>
                @php
                    $totalImporte = 0;
                @endphp
                @foreach ($totalMonedero as $monedero)
                    <tr>
                        <td style="text-align: right">{{ $monedero->NomClienteCloud }} </td>
                        <td style="text-align: right">${{ number_format($monedero->importe, 2) }}</td>
                    </tr>
                    @php
                        $totalImporte += $monedero->importe;
                    @endphp
                @endforeach
                <tr class="totales">
                    <td style="text-align: right">Total Dinero Electrónico: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($totalImporte, 2) }}</td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Crédito Quincenal: </td>
                    <td style="text-align: right">${{ number_format($creditoQuincenal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Crédito Semanal: </td>
                    <td style="text-align: right">${{ number_format($creditoSemanal, 2) }}</td>
                </tr>
                <tr class="totales">
                    <td style="text-align: right">Total Créditos: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                    </td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Tarjeta Débito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaDebito, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Tarjeta Crédito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaCredito, 2) }}</td>
                </tr>
                <tr class="totales">
                    <td style="text-align: right">Total Tarjeta: </td>
                    <td style="font-weight: bold; color: red; text-align: right">
                        ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                    </td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Total Transferencia: </td>
                    <td style="font-weight: bold; color: red; text-align: right">
                        ${{ number_format($totalTransferencia, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Total Factura: </td>
                    <td style="font-weight: bold; color: red; text-align: right">${{ number_format($totalFactura, 2) }}
                    </td>
                </tr>
                <tr class="totales">
                    <td style="text-align: right">Total Efectivo: </td>
                    <td style="font-weight: bold; color: red; text-align:right">${{ number_format($totalEfectivo, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
