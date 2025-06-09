<p>
    <b> Tienda - {{ $data['tienda'] }} </b>
</p>

<p>
    <b> Fechas:
        {{ strftime('%d %B %Y', strtotime($data['fecha1'])) }},
        {{ strftime('%d %B %Y', strtotime($data['fecha2'])) }}
    </b>
</p>

<br>

<table>
    <thead>
        <tr>
            <th style="font-weight: 500">Folio</th>
            <th style="font-weight: 500">Codigo</th>
            <th style="font-weight: 500">Articulo</th>
            <th style="font-weight: 500">Merma</th>
            <th style="font-weight: 500">Cantidad</th>
            <th style="font-weight: 500">Almacen</th>
            <th style="font-weight: 500">Cuenta</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data['mermas']) == 0)
            <tr>
                <td colspan="4">No hay mermas </td>
            </tr>
        @endif
        @foreach ($data['mermas'] as $merma)
            <tr>
                <td style="width: 100px">{{ $merma->FolioMerma }}</td>
                <td>{{ $merma->CodArticulo }}</td>
                <td style="width: 240px">{{ $merma->NomArticulo }}</td>
                <td style="width: 150px">{{ $merma->NomTipoMerma }} </td>
                <th>{{ $merma->CantArticulo }}</th>
                <th>{{ $merma->Almacen }}</th>
                <th style="width: 150px">
                    {{ empty($merma->Libro) ? '?' : $merma->Libro }}.{{ empty($merma->CentroCosto) ? '?' : $merma->CentroCosto }}.{{ empty($merma->Cuenta) ? '?' : $merma->Cuenta }}.{{ empty($merma->SubCuenta) ? '?' : $merma->SubCuenta }}.{{ empty($merma->InterCosto) ? '?' : $merma->InterCosto }}.{{ empty($merma->IdTipoArticulo) ? '?' : $merma->IdTipoArticulo }}.{{ $merma->Futuro != '0' ? '?' : $merma->Futuro }}
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
