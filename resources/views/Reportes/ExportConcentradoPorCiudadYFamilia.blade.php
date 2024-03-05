<table>
    <thead>
        <tr>
            <th style="text-align: center; font-weight: 600; font-size: 20px" colspan="4">CONCENTRADO POR CIUDAD Y
                FAMILIA
            </th>
        </tr>
        <tr>
            <th>Ciudad</th>
            <th>Familia</th>
            <th>Kilos</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        @if ($data->count() == 0)
            <tr>
                <td colspan="4">No hay productos </td>
            </tr>
        @else
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->NomCiudad }}</td>
                    <td>{{ $item->NomGrupo }}</td>
                    <td style="text-align: right;">{{ number_format($item->kilos, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->importe, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <td style="text-align: center; font-weight: 600; font-size: 16px" colspan="2">Totales:
            </td>
        </tr>
        <tr>
            <td>Tipo de pago</td>
            <td>Importes</td>
            <td>Kilos</td>
        </tr>
        @foreach ($totales as $key => $total)
            <div class="col pb-3">
                <div class="card p-3" style="border-radius: 20px;">
                    <div class="d-flex flex-row gap-2 justify-content-between">
                        <span class="text-secondary" style="font-size: 12px">{{ $key }} </span>
                        <b class="{{ number_format($total, 2) == 0 ? 'eliminar' : 'send' }}">
                            ${{ number_format($total, 2) }}
                        </b>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary" style="font-size: 12px">KILOS: </span>
                        <b class="{{ number_format($kilos[$key], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 12px"> {{ number_format($kilos[$key], 2) }}
                        </b>
                    </div>
                </div>
            </div>
            <tr>
                <td>{{ $key }}</td>
                <td style="text-align: right;">{{ number_format($total, 2) }}</td>
                <td style="text-align: right;">{{ number_format($kilos[$key], 2) }}</td>
            </tr>
        @endforeach
    </tfoot>
</table>
