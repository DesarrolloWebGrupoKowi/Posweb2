<table>
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombre articulo</th>
            <th>Menudeo</th>
            <th>Minorista</th>
            <th>Detalle</th>
            <th>Empleados y socios</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="7">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $precio)
                <tr>
                    <td>{{ $precio->CodArticulo }}</td>
                    <td>{{ $precio->NomArticulo }}</td>
                    <td>{{ number_format($precio->Menudeo, 2) }}</td>
                    <td>{{ number_format($precio->Minorista, 2) }}</td>
                    <td>{{ number_format($precio->Detalle, 2) }}</td>
                    <td>{{ number_format($precio->EmpySoc, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
