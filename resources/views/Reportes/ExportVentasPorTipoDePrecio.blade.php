<table>
    <thead>
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
                <td colspan="8">No hay productos </td>
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
</table>
