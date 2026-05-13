<table>
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>Tienda</th>
            @if ($agrupado)
                <th>Fecha</th>
            @endif
            <th>Grupo</th>
            @if (!$agrupadoArticulo)
                <th>Lista precios</th>
            @endif
            <th>Código</th>
            <th>Articulo</th>
            <th>Cantidad</th>
            @if (!$agrupadoArticulo)
                <th>Precio</th>
            @endif
            <th>Iva</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="9">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->NomCiudad }}</td>
                    <td>{{ $item->NomTienda }}</td>
                    @if ($agrupado)
                        <td>{{ $item->FechaVenta }}</td>
                    @endif
                    <td>{{ $item->NomGrupo }}</td>
                    @if (!$agrupadoArticulo)
                        <td>{{ $item->NomListaPrecio }}</td>
                    @endif
                    <td>{{ $item->CodArticulo }}</td>
                    <td>{{ $item->NomArticulo }}</td>
                    <td>{{ $item->Peso }}</td>
                    @if (!$agrupadoArticulo)
                        <td>{{ $item->PrecioArticulo }}</td>
                    @endif
                    <td>{{ $item->Iva }}</td>
                    <td>{{ $item->Importe }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
