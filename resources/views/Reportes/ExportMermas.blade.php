<table>
    <thead>
        <tr>
            <th style="font-weight: 500">Folio</th>
            <th style="font-weight: 500">Tienda</th>
            <th style="font-weight: 500">Fecha captura</th>
            <th style="font-weight: 500">Hora</th>
            <th style="font-weight: 500">Tipo de merma</th>
            <th style="font-weight: 500">Codigo</th>
            <th style="font-weight: 500">Articulo</th>
            <th style="font-weight: 500">Cantidad</th>
            <th style="font-weight: 500">Fecha interfazado</th>
            <th style="font-weight: 500">Hora</th>
            <th style="font-weight: 500">{{--  --}}Comentario</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data['concentrado']) == 0)
            <tr>
                <td colspan="4">No hay mermas </td>
            </tr>
        @endif
        @foreach ($data['concentrado'] as $merma)
            <tr>
                <td style="width: 100px">{{ $merma->FolioMerma }}</td>
                <td style="width: 280px">{{ $merma->NomTienda }}</td>
                <td style="width: 120px">{{ strftime('%d/%m/%Y', strtotime($merma->FechaCaptura)) }}</td>
                <td>{{ strftime('%H:%M', strtotime($merma->FechaCaptura)) }}</td>
                <td style="width: 150px">{{ $merma->NomTipoMerma }}</td>
                <td style="text-align: right">{{ $merma->CodArticulo }}</td>
                <td style="width: 240px">{{ $merma->NomArticulo }}</td>
                <td>{{ $merma->CantArticulo }}</td>
                <td style="width: 120px">
                    {{ $merma->FechaInterfaz ? strftime('%d/%m/%Y', strtotime($merma->FechaInterfaz)) : '' }} </td>
                <td> {{ $merma->FechaInterfaz ? strftime('%H:%M', strtotime($merma->FechaInterfaz)) : '' }} </td>
                <td style="width: 300px">{{ $merma->Comentario }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
