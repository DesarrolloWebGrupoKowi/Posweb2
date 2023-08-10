@extends('plantillaBase.masterblade')
@section('title', 'Preparados asignados')
@section('contenido')

    <div style="min-height: 70vh" class="container cuchi">
        <div>
            <h2 class="titulo">Detalle de asignados</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="row">
            <form class="col-8 d-flex gap-2" action="/DetalleAsignados">
                <div class="col-3">
                    <input type="date" name="fecha" value="{{ $fecha }}" class="form-control"
                        placeholder="Nombre del preparado" autofocus>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </form>
            <div class="col-4">
                <a href="/AsignarPreparados" class="btn btn-sm btnAgregar alinearDerecha">
                    <i class="fa fa-eye"></i> Lista de preparados
                </a>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-responsive table-sm table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tienda</th>
                        <th>Fecha</th>
                        <th>Cantidad Enviada</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($asignados) == 0)
                        <tr>
                            <td colspan="8">No se encuentra ningun preparado en proceso</td>
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
        <div class="mt-5 d-flex justify-content-center">
            {!! $asignados->links() !!}
        </div>
    </div>

@endsection
