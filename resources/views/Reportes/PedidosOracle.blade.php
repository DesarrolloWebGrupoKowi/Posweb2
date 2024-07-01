@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos Oracle')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Pedidos Oracle'])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            @include('components.table-search', ['placeholder'=>'POS_000000'])
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">ORDEN ORACLE CLOUD</th>
                        <th>TICKET</th>
                        <th>STATUS</th>
                        <th>FECHA CORTE</th>
                        <th class="rounded-end">TIENDA</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $concentrado, 'colspan' => 5])
                    @foreach ($concentrado as $item)
                        <tr>
                            <td>{{ substr_replace($item->Source_Transaction_Identifier, '_', 3, 0) }}</td>
                            <td>{{ $item->IdTicket }}</td>
                            <td>{{ $item->MENSAJE_ERROR }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaVenta)) }}</td>
                            <td>{{ $item->NomTienda }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
