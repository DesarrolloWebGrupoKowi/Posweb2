<p>
    <b> Créditos Empleado - {{ empty($data['chkNomina']) ? $data['nomTipoNomina'] : $data['empleado'] }} </b>
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
            <th style="font-weight: 500">Ciudad</th>
            <th style="font-weight: 500">Tienda</th>
            <th style="font-weight: 500">Nómina</th>
            <th style="font-weight: 500">Empleado</th>
            <th style="font-weight: 500">Importe</th>
            <th style="font-weight: 500">Sistema</th>
        </tr>
    </thead>
    <tbody>
        @if (count($data['creditos']) == 0)
            <tr>
                <td colspan="4">No hay productos </td>
            </tr>
        @endif
        @foreach ($data['creditos'] as $credito)
            <tr>
                <td style="width: 120px">{{ $credito->NomCiudad }}</td>
                <td style="width: 250px">{{ $credito->NomTienda }}</td>
                <td>{{ $credito->NumNomina }}</td>
                <td style="width: 400px">{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                <th style="width: 100px">{{ $credito->ImporteCredito }}</th>
                <th style="width: 120px">
                    @if ($credito->isSistemaNuevo == 1)
                        Sistema nuevo
                    @else
                        Sistema viejo
                    @endif
                </th>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <th></th>
            <th></th>
            <th style="text-align: right; font-weight: 500">Total: </th>
            <th style="font-weight: 500">{{ round($data['totalAdeudo'], 2) }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
