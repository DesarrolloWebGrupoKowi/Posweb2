@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Precios')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Módulo de Precios'])
            <div class="d-flex align-items-center justify-content-end gap-4">
                <form class="d-flex align-items-center justify-content-end gap-4" action="/DetallePrecios" method="get">
                    <div class="input-group" style="max-width: 300px">
                        <input class="form-control" type="text" name="txtFiltro" id="txtFiltro"
                            placeholder="Escribre el nombre de la ciudad" value="{{ $txtFiltro }}">
                        <div class="input-group-append">
                            <button class="input-group-text"><span class="material-icons">search</span></button>
                        </div>
                    </div>
                </form>
                <a href="/ExportExcelDetallePrecios" class="input-group-text text-decoration-none">
                    <i class="fa fa-file-excel-o pe-2"></i> Excel
                </a>
            </div>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Codigo</th>
                        <th>Nombre articulo</th>
                        <th>Menudeo</th>
                        <th>Minorista</th>
                        <th>Detalle</th>
                        <th class="rounded-end">Empleados y socios</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle">
                    @if ($precios->count() == 0)
                        <tr>
                            <td colspan="7">No hay productos <i class="fa fa-exclamation-circle"></i></td>
                        </tr>
                    @else
                        @foreach ($precios as $precio)
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
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {!! $precios->links() !!}
    </div>

@endsection
