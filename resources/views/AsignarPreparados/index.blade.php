@extends('plantillaBase.masterblade')
@section('title', 'Lista de preparado')
@section('contenido')

    <div style="min-height: 70vh" class="container cuchi">
        <div>
            <h2 class="titulo">Lista de preparados</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="row">
            <form class="col-8 d-flex gap-2" action="/AsignarPreparados">
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
                <a href="/DetalleAsignados" class="btn btn-sm btnAgregar alinearDerecha">
                    <i class="fa fa-eye"></i> Ver detalle asignados
                </a>
            </div>
        </div>
        <div class="col-12">
            <table class="table table-responsive table-sm table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Cantidad libre</th>
                        <th>Detalle</th>
                        <th>Tiendas</th>
                        <th>Asignar</th>
                        <th>Regresar</th>
                        <th>Finalizar</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($preparados) == 0)
                        <tr>
                            <td colspan="9">No se encuentra ningun preparado en proceso</td>
                        </tr>
                    @else
                        @foreach ($preparados as $preparado)
                            <tr>
                                <td>{{ $preparado->Nombre }}</td>
                                <td>{{ ucfirst(\Carbon\Carbon::parse($preparado->Fecha)->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                                </td>
                                <td>{{ $preparado->Cantidad }} piezas</td>
                                <td>{{ $preparado->Cantidad - $preparado->CantidadAsignada }} piezas</td>
                                <td>
                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalShowDetails{{ $preparado->IdPreparado }}">
                                        <span class="material-icons">visibility</span>
                                    </button>
                                    @include('AsignarPreparados.ModalShowDetails')
                                </td>
                                <td>
                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalShowTiendas{{ $preparado->IdPreparado }}">
                                        <span class="material-icons" style="color: #333">assignment</span>
                                    </button>
                                    @include('AsignarPreparados.ModalShowTiendas')
                                </td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalAsignar{{ $preparado->IdPreparado }}"
                                        {{ $preparado->CantidadAsignada == $preparado->Cantidad || $preparado->IdCatStatusPreparado != 2 ? 'disabled' : '' }}><span
                                            class="material-icons send">send</span></button>
                                    @include('AsignarPreparados.ModalAsignar')
                                </td>
                                <td>
                                    <button style="font-size: 18px" class="btn btn-sm eliminar" data-bs-toggle="modal"
                                        data-bs-target="#ModalRegresar{{ $preparado->IdPreparado }}"
                                        {{ $preparado->CantidadAsignada > 0 || $preparado->IdCatStatusPreparado != 2 ? 'disabled' : '' }}>
                                        <i class="fa fa-reply-all"></i>
                                    </button>
                                    @include('AsignarPreparados.ModalRegresar')
                                </td>
                                <td>
                                    <button style="font-size: 22px" class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalFinalizar{{ $preparado->IdPreparado }}"
                                        {{ $preparado->IdCatStatusPreparado != 2 ? 'disabled' : '' }}>
                                        <i class="fa fa-check-circle-o"></i>
                                    </button>
                                    @include('AsignarPreparados.ModalFinalizar')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {!! $preparados->links() !!}
        </div>
    </div>

@endsection
