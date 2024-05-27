@extends('plantillaBase.masterbladeNewStyle')
@section('title', 'Lista de preparados')
@section('dashboardWidth', 'width-general')
@section('contenido')

    <div class="container-fluid pt-4 width-general">
        {{-- Titulo --}}
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Lista de preparados'])
            <div class="">
                <a href="/Preparados" class="btn btn-primary ">
                    <small><i class="fa fa-plus-circle pe-1"></i>Agregar preparado</small>
                </a>
                <a href="/DetalleAsignados" class="btn btn-sm btn-dark">
                    <i class="fa fa-eye"></i> Ver detalle asignados
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4" action="/AsignarPreparados">
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
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Cantidad libre</th>
                        <th>Detalle</th>
                        <th>Tiendas</th>
                        <th>Asignar</th>
                        <th>Regresar</th>
                        <th class="rounded-end">Finalizar</th>
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
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalShowDetails{{ $preparado->IdPreparado }}">
                                        <span class="material-icons editar">visibility</span>
                                    </button>
                                    @include('AsignarPreparados.ModalShowDetails')
                                </td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalShowTiendas{{ $preparado->IdPreparado }}">
                                        <span class="material-icons">assignment</span>
                                    </button>
                                    @include('AsignarPreparados.ModalShowTiendas')
                                </td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalAsignar{{ $preparado->IdPreparado }}"
                                        {{ $preparado->CantidadAsignada == $preparado->Cantidad || $preparado->IdCatStatusPreparado != 2 ? 'disabled' : '' }}>
                                        <span class="material-icons send">send</span>
                                    </button>
                                    @include('AsignarPreparados.ModalAsignar')
                                </td>
                                <td>
                                    <button style="font-size: 18px" class="btn eliminar" data-bs-toggle="modal"
                                        data-bs-target="#ModalRegresar{{ $preparado->IdPreparado }}"
                                        {{ $preparado->CantidadAsignada > 0 || $preparado->IdCatStatusPreparado != 2 ? 'disabled' : '' }}>
                                        <i class="fa fa-reply-all"></i>
                                    </button>
                                    @include('AsignarPreparados.ModalRegresar')
                                </td>
                                <td>
                                    <button style="font-size: 22px" class="btn" data-bs-toggle="modal"
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
