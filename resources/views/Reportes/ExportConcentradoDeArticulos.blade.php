<table>
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>Tienda</th>
            <th>Grupo</th>
            <th>Código</th>
            <th>Articulo</th>
            <th>Cantidad</th>
            <th>Importe</th>
            <th>Precio</th>
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
                    <td>{{ $item->NomCiudad }}</td>
                    <td>{{ $item->NomTienda }}</td>
                    <td>{{ $item->NomGrupo }}</td>
                    <td>{{ $item->CodArticulo }}</td>
                    <td>{{ $item->NomArticulo }}</td>
                    <td>{{ number_format($item->Peso, 3) }}</td>
                    <td>{{ number_format($item->Importe, 2) }}</td>
                    <td>{{ number_format($item->PrecioArticulo, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
