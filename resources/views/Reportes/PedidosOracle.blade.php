@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Pedidos Oracle')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Pedidos Oracle'])
            {{-- <div>
                <form action="/ExportReporteDineroElectronido" method="GET">
                    <input type="hidden" name="pos" value="{{ substr_replace($pos, '_', 3, 0) }}">
                    <button type="submit" class="input-group-text text-decoration-none btn-excel">
                        <i class="fa fa-file-excel-o pe-2"></i> Exportar
                    </button>
                </form>
            </div> --}}
            <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ReportePedidosOracle">
                <div class="col-auto">
                    <input type="text" class="form-control" name="pos" id="pos"
                        value="{{ $pos ? substr_replace($pos, '_', 3, 0) : '' }}" placeholder="Buscar...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                    @if ($concentrado->count() == 0)
                        <tr>
                            <th colspan="5"><i class="fa fa-exclamation-triangle"></i> No Se Encontraron Registros!
                            </th>
                        </tr>
                    @else
                        @foreach ($concentrado as $item)
                            <tr>
                                <td>{{ substr_replace($item->Source_Transaction_Identifier, '_', 3, 0) }}</td>
                                <td>{{ $item->IdTicket }}</td>
                                <td>{{ $item->MENSAJE_ERROR }}</td>
                                <td>{{ $item->FechaVenta }}</td>
                                <td>{{ $item->NomTienda }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
