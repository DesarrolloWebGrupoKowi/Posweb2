<table>
    <thead>
        <tr>
            <th>Fecha Compra</th>
            <th>Tienda</th>
            <th>Nómina</th>
            <th>Empleado</th>
            <th>Empresa</th>
            <th>Ticket</th>
            <th>Codigo</th>
            <th>Articulo</th>
            <th>Importe</th>
            <th>Tipo Empleado</th>
            <th>Crédito</th>
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
                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaVenta)) }}</td>
                    <td>{{ $item->NomTienda }}</td>
                    <td>{{ $item->NumNomina }}</td>
                    <td>{{ $item->Nombre }} {{ $item->Apellidos }}</td>
                    <td>{{ $item->Empresa }}</td>
                    <td>{{ $item->IdTicket }}</td>
                    <td>{{ $item->CodArticulo }}</td>
                    <td>{{ $item->NomArticulo }}</td>
                    <td>{{ $item->ImporteArticulo }}</td>
                    <td>
                        {{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] }}
                    </td>
                    <td>{{ $item->StatusCredito === '0' ? 'Si' : '' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
