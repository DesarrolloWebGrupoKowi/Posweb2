@extends('plantillaBase.masterbladeNewStyle')
@section('title', 'Preparados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Preparados'])
                <div class="d-flex align-items-center gap-2">
                    {{-- <a href="/Preparados" class="btn btn-dark-outline btn-sm"> Agregar preparado </a> --}}
                    <button type="button" class="btn btn-dark" role="tooltip" title="Agregar preparado" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarPreparado">
                        Agregar preparado @include('components.icons.plus-circle')
                    </button>
                    <a href="/DetalleAsignados" class="btn btn-dark" title="Historial de preparados">
                        Historial preparados @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/AsignarPreparados">
                <div class="d-flex align-items-center gap-2">
                    <label for="fecha" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha"
                        id="fecha" value="{{ $fecha }}" autofocus>
                </div>
                <button type="submit" class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Cantidad libre</th>
                        <th>Costo</th>
                        <th>Detalle</th>
                        <th class="rounded-end text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $preparados, 'colspan' => 8])
                    @foreach ($preparados as $preparado)
                        <tr>
                            <td>{{ $preparado->IdPreparado }}</td>
                            <td>{{ $preparado->Nombre }}</td>
                            <td>{{ ucfirst(\Carbon\Carbon::parse($preparado->Fecha)->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                            </td>
                            <td>{{ $preparado->Cantidad }} piezas</td>
                            <td>{{ $preparado->Cantidad - $preparado->CantidadAsignada }} piezas</td>
                            <td>${{ round($preparado->Total, 2) }}</td>
                            <td>{{ count($preparado->Detalle) }} productos</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $preparado->IdPreparado }}"
                                        title="Editar de preparado">
                                        @include('components.icons.edit')
                                    </button>
                                    <button
                                        class="{{ Session::get('modalshow') == $preparado->IdPreparado ? 'modalOpen' : '' }} btn-table"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalShowDetails{{ $preparado->IdPreparado }}"
                                        title="Detalle de preparado">
                                        @include('components.icons.list')
                                    </button>
                                    <button
                                        class="{{ Session::get('id') == $preparado->preparado ? 'modalOpen' : '' }} btn-table  btn-table-show"
                                        data-bs-toggle="modal" data-bs-target="#ModalAsignar{{ $preparado->IdPreparado }}"
                                        title="Tiendas"
                                        {{ count($preparado->Detalle) == 0 || !$preparado->Cantidad ? 'disabled' : '' }}>
                                        @include('components.icons.house')
                                    </button>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarPreparado{{ $preparado->IdPreparado }}"
                                        title="Eliminar preparado" {{ count($preparado->Tiendas) > 0 ? 'disabled' : '' }}>
                                        @include('components.icons.delete')
                                    </button>
                                    <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                        data-bs-target="#ModalFinalizar{{ $preparado->IdPreparado }}"
                                        {{ count($preparado->Tiendas) == 0 ? 'disabled' : '' }} title="Finalizar">
                                        @include('components.icons.check')
                                    </button>
                                </div>
                                @include('Preparados.ModalEditar')
                                @include('AsignarPreparados.ModalShowDetails')
                                @include('AsignarPreparados.ModalAsignar')
                                @include('Preparados.ModalEliminarPreparado')
                                @include('AsignarPreparados.ModalFinalizar')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $preparados])
        </div>
    </div>
    @include('Preparados.ModalAgregarPreparado')
    <form id="form-update" method="POST">
        @csrf
        <input type="hidden" name="IdListaPrecio" value="5">
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('change', e => {
            if (e.target.matches('.form-select')) {
                console.log(e.target.value);
                console.log(e.target.getAttribute('data-id'));
                document.querySelectorAll('.form-select').forEach(element => {
                    element.value = e.target.value;
                });
                let form = document.getElementById('form-update');
                form.action = '/EditarListaPreciosPreparados/' + e.target.getAttribute('data-id');
                form.IdListaPrecio.value = e.target.value;
                form.submit();
            }
        })
    </script>
@endsection
