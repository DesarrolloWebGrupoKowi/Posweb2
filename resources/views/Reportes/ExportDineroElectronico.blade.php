<table>
    <thead>
        <tr>
            <th>Tienda</th>
            <th>Día</th>
            <th>Semanal Crédito</th>
            <th>Semanal Contado</th>
            <th>Quincenal Crédito</th>
            <th>Quincenal Contado</th>
            <th>Contado</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="7">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->NomTienda }}</td>
                    <td>{{ $item->Fecha }}</td>
                    <td>{{ $item->semanal_creadito }}</td>
                    <td>{{ $item->semanal_contado }}</td>
                    <td>{{ $item->quincenal_creadito }}</td>
                    <td>{{ $item->quincenal_contado }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
