<table>
    <thead>
        <tr>
            <th>IdArticulo</th>
            <th>CodArticulo</th>
            <th>NomArticulo</th>
            <th>Amece</th>
            <th>UOM</th>
            <th>Peso</th>
            <th>Tercero</th>
            <th>CodEtiqueta</th>
            <th>PrecioRecorte</th>
            <th>Factor</th>
            <th>Inventory_Item_Id</th>
            <th>IdTipoArticulo</th>
            <th>IdFamilia</th>
            <th>IdGrupo</th>
            <th>Iva</th>
            <th>Status</th>
            <th>NomGrupo</th>
            <th>NomFamilia</th>
            <th>NomTipoArticulo</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="19">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $precio)
                <tr>
                    <td>{{ $precio->IdArticulo }}</td>
                    <td>{{ $precio->CodArticulo }}</td>
                    <td>{{ $precio->NomArticulo }}</td>
                    <td>{{ $precio->Amece }}</td>
                    <td>{{ $precio->UOM }}</td>
                    <td>{{ $precio->Peso }}</td>
                    <td>{{ $precio->Tercero }}</td>
                    <td>{{ $precio->CodEtiqueta }}</td>
                    <td>{{ $precio->PrecioRecorte }}</td>
                    <td>{{ $precio->Factor }}</td>
                    <td>{{ $precio->Inventory_Item_Id }}</td>
                    <td>{{ $precio->IdTipoArticulo }}</td>
                    <td>{{ $precio->IdFamilia }}</td>
                    <td>{{ $precio->IdGrupo }}</td>
                    <td>{{ $precio->Iva }}</td>
                    <td>{{ $precio->Status }}</td>
                    <td>{{ $precio->NomGrupo }}</td>
                    <td>{{ $precio->NomFamilia }}</td>
                    <td>{{ $precio->NomTipoArticulo }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
