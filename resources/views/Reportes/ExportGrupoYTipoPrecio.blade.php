<table>
    <thead>
        <tr>
            <th style="text-align: center; font-weight: 600; font-size: 20px" colspan="5">CONCENTRADO POR GRUPO Y TIPO
                DE PRECIO
            </th>
        </tr>
        <tr>
            <th>TIENDA</th>
            <th>GRUPO</th>
            <th>TIPO PRECIO</th>
            <th>CANTIDAD</th>
            <th>IMPORTE</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="5">No hay datos... </td>
            </tr>
        @else
            @php
                $importes = 0;
                $kilos = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->NomTienda }}</td>
                    <td>{{ $item->NomGrupo }}</td>
                    <td>{{ $item->NomListaPrecio }}</td>
                    <td style="text-align: right;">{{ number_format($item->kilos, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->importe, 2) }}</td>
                    @php
                        $kilos += $item->kilos;
                        $importes += $item->importe;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td class="text-end fw-bold">Total:</td>
                <td class="text-end">{{ number_format($kilos, 2) }}</td>
                <td class="text-end">${{ number_format($importes, 2) }}</td>
            </tr>
        @endif
    </tbody>
</table>
