@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Precios')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Módulo de Precios'])
                <div class="d-flex align-items-center justify-content-end gap-4">
                    <a href="/ExportExcelDetallePrecios" class="input-group-text text-decoration-none btn-excel">
                        @include('components.icons.excel') Exportar
                    </a>
                </div>
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-4 pb-2" action="/DetallePrecios" method="get">
                <div class="d-flex align-items-center gap-2">
                    <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="text" name="txtFiltro"
                        id="txtFiltro" value="{{ $txtFiltro }}" autofocus>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Codigo</th>
                        <th>Nombre articulo</th>
                        <th>Menudeo</th>
                        <th>Minorista</th>
                        <th>Detalle</th>
                        <th class="rounded-end">Empleados y socios</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle">
                    @include('components.table-empty', ['items' => $precios, 'colspan' => 7])
                    @foreach ($precios as $precio)
                        <tr>
                            <td>{{ $precio->IdArticulo }}</td>
                            <td>{{ $precio->CodArticulo }}</td>
                            <td>{{ $precio->NomArticulo }}</td>
                            <td>{{ number_format($precio->Menudeo, 2) }}</td>
                            <td>{{ number_format($precio->Minorista, 2) }}</td>
                            <td>{{ number_format($precio->Detalle, 2) }}</td>
                            <td>{{ number_format($precio->EmpySoc, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $precios])
        </div>
    </div>
@endsection
