<table>
    <thead>
        <tr>
            <th>Folio</th>
            <th>Fecha</th>
            <th>Tienda Origen</th>
            <th>Tienda Destino</th>
            <th>Código</th>
            <th>Artículo</th>
            <th>Cantidad</th>
            <th>En linea</th>
        </tr>
    </thead>

    <tbody>
        @include('components.table-empty', ['items' => $data, 'colspan' => 11])

        @foreach ($data as $item)
            <tr>
                <td>{{ $item->IdTransferencia }}</td>
                <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i') }}</td>
                <td>{{ $item->tiendaOrigen }}</td>
                <td>{{ $item->tiendaDestino }}</td>
                <td>{{ $item->CodArticulo }}</td>
                <td>{{ $item->NomArticulo }}</td>
                <td>{{ $item->CantidadTrasferencia }}</td>
                <td>{{ $item->Subir === '1' ? 'Si' : 'No' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
