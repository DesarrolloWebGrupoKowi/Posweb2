<table>
    <thead>
        <tr>
            <th style="text-align: center; font-weight: 600; font-size: 20px" colspan="5">VENTAS POR TIPO DE PRECIO
            </th>
        </tr>
        <tr>
            <th>Tienda</th>
            <th>Tipo precio</th>
            <th>Kilos</th>
            <th>Importe</th>
            <th>Clientes</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="5">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->NomTienda }}</td>
                    <td>{{ $item->NomListaPrecio }}</td>
                    <td style="text-align: right;">{{ number_format($item->kilos, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->importe, 2) }}</td>
                    <td style="text-align: right;">{{ $item->tickets }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <td style="text-align: center; font-weight: 600; font-size: 16px" colspan="2">Totales:
            </td>
        </tr>
        <tr>
            <td>Tipo de pago</td>
            <td>Cantidad</td>
        </tr>
        <tr>
            <td>DETALLE</td>
            <td style="text-align: right;">{{ number_format($totales['DETALLE'], 2) }}</td>
        </tr>
        <tr>
            <td>MENUDEO</td>
            <td style="text-align: right;">{{ number_format($totales['MENUDEO'], 2) }}</td>
        </tr>
        <tr>
            <td>MINORISTA</td>
            <td style="text-align: right;">{{ number_format($totales['MINORISTA'], 2) }}</td>
        </tr>
        <tr>
            <td>EMPYSOC</td>
            <td style="text-align: right;">{{ number_format($totales['EMPYSOC'], 2) }}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td style="text-align: right;">{{ number_format($totales['TOTAL'], 2) }}</td>
        </tr>
    </tfoot>
</table>
