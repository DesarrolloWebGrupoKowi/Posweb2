@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Preparados asignados')
@section('dashboardWidth', 'width-general')
@section('contenido')

    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-between align-items-center pb-2">
            @include('components.title', ['titulo' => 'Detalle de asignados'])
            <div class="">
                <a href="/AsignarPreparados" class="btn btn-sm btn-dark">
                    <i class="fa fa-eye"></i> Lista de preparados
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4" action="/DetalleAsignados">
            <div class="input-group" style="max-width: 300px">
                <input type="date" class="form-control" name="fecha" value="{{ $fecha }}">
                <div class="input-group-append">
                    <button type="submit" class="input-group-text">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Nombre</th>
                        <th>Tienda</th>
                        <th>Fecha</th>
                        <th>Cantidad Enviada</th>
                        <th class="rounded-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($asignados) == 0)
                        <tr>
                            <td colspan="8">No se encuentra ningun preparado asignado</td>
                        </tr>
                    @else
                        @foreach ($asignados as $asignado)
                            <tr>
                                <td>{{ $asignado->Nombre }}</td>
                                <td>{{ $asignado->NomTienda }}</td>
                                <td>{{ ucfirst(\Carbon\Carbon::parse($asignado->Fecha)->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                                </td>
                                <td>{{ $asignado->CantidadEnvio }} piezas</td>
                                <td>
                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalShowDetails{{ $asignado->IdDatAsignacionPreparado }}">
                                        <span class="material-icons" style="color: #333">assignment</span>
                                    </button>
                                    @include('AsignacionPreparados.ModalShowDetails')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {!! $asignados->links() !!}
        </div>
    </div>

@endsection
