<table>
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>Tienda</th>
            <th>Fecha</th>
            <th>Tickets</th>
            <th>Importe</th>
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
                    <td>{{ \Carbon\Carbon::parse($item->Fecha)->format('d/m/Y') }}</td>
                    <td>{{ $item->Tickets }}</td>
                    <td>{{ $item->Importe }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
