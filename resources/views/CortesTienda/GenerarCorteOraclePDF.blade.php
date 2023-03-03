<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .titulo,
        .tienda {
            text-align: center;
        }

        #tblCustomerTienda,
        #tblFactura {
            font-size: 10px;
            /*border-collapse: collapse;*/
            width: 100%;
        }

        #sumatorias {
            font-size: 11px;
            /*border-collapse: collapse;*/
            width: 100%;
        }

        table,
        td {
            border-bottom: 1px solid rgb(95, 95, 95);
            border-collapse: collapse;
        }

        th {
            text-align: left;
            border-bottom: 1px solid rgb(0, 0, 0);
            background-color: #000000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        #DivSumatorias {
            float: right;
            ;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Corte Diario de Tienda</title>
</head>

<body>
    <div class="container">
        <caption class="titulo">{{ $titulo }}</caption>
        <caption class="tienda">{{ $nomTienda }}</caption>
        <caption class="titulo">CAJA: {{ $numCaja == 0 ? 'TODAS' : $numCaja }}</caption>
        <caption style="text-align: right">{{ $fecha }}</caption>
        <br>
        @foreach ($cortesTienda as $corteTienda)
            @foreach ($corteTienda->Customer as $customer)
            @endforeach
            <table id="tblCustomerTienda">
                <caption style="text-align: left">
                    {{ $customer->NomClienteCloud }} -
                    @foreach ($corteTienda->PedidoOracle as $pedidoOracle)
                        @if (empty($pedidoOracle->Source_Transaction_Identifier))
                            SIN PEDIDO
                        @else
                            {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }} -
                        @endif
                    @endforeach
                </caption>
                <thead>
                    <tr class="cab">
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
                        <th>Pedido</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($corteTienda->CorteTiendaOracle as $detalleCorte)
                        <tr class="striped">
                            <td>{{ $detalleCorte->CodArticulo }}</td>
                            <td>{{ $detalleCorte->NomArticulo }}</td>
                            <td>{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                            <td>{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                            <td>{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                            @if (empty($detalleCorte->Source_Transaction_Identifier))
                                <td>SIN PEDIDO</td>
                            @else
                                <td>
                                    {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                </td>
                            @endif
                            @if (
                                (empty($detalleCorte->STATUS) || $detalleCorte->STATUS == 'NULL') &&
                                    empty($detalleCorte->MENSAJE_ERROR) &&
                                    empty($detalleCorte->Batch_Name))
                                <td>SIN PROCESAR</td>
                            @endif
                            @if ($detalleCorte->STATUS == 'ERROR')
                                <td>ERROR</td>
                            @endif
                            @if ($detalleCorte->STATUS == 'PROCESADO')
                                <td>{{ $detalleCorte->STATUS }}</td>
                            @endif
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                            $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <!--MONEDERO ELECTRONICO QUINCENAL-->
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 4)
                        <tr>
                            <td></td>
                            <td style="text-align:center; font-weight: bold;">Dinero Electrónico: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="color: red; font-weight: bold;">{{ number_format($totalMonederoQuincenal, 2) }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                    <!--TERMINA MONEDERO ELECTRONICO QUINCENAL-->

                    <!--MONEDERO ELECTRONICO SEMANAL-->
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 3)
                        <tr>
                            <td></td>
                            <td style="text-align:center; font-weight: bold;">Dinero Electrónico: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="color: red; font-weight: bold;">{{ number_format($totalMonederoSemanal, 2) }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                    <!--TERMINA MONEDERO ELECTRONICO SEMANAL-->
                    <tr>
                        <td></td>
                        <td style="text-align:center; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">{{ number_format($sumImporte, 2) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endforeach
    </div>
    <div class="container">
        @foreach ($facturas as $factura)
            <table id="tblFactura">
                @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                    <caption style="text-align: left">FALTA LIGAR CLIENTE - {{ $factura->NomCliente }}</caption>
                @else
                    <caption style="text-align: left">{{ $factura->NomCliente }}</caption>
                @endif
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
                        <th>Pedido</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($factura->Factura as $detalleFactura)
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
                    <tr>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold;">{{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">{{ number_format($sumImporte, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endforeach
    </div>
    <br>
    <!--SUMATORIAS FINALES-->
    <div id="DivSumatorias" class="container">
        <table id="sumatorias">
            <tbody>
                <tr>
                    <td style="text-align: right">Dinero Electrónico Crédito Quincenal: </td>
                    <td style="text-align: right">${{ number_format($totalMonederoQuincenal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Dinero Electrónico Crédito Semanal: </td>
                    <td style="text-align: right">${{ number_format($totalMonederoSemanal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Total Dinero Electrónico: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) }}</td>
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
                <tr>
                    <td style="text-align: right">Total Créditos: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}</td>
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
                <tr>
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
                <tr>
                    <td style="text-align: right">Total Efectivo: </td>
                    <td style="font-weight: bold; color: red; text-align:right">${{ number_format($totalEfectivo, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
