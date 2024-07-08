@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Estados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Estados'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar Estado @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>


        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/CatEstados"
                method="get">
                <div class="d-flex align-items-center gap-2">
                    <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <select class="form-select rounded" style="line-height: 18px" name="Activo" id="Activo">
                        <option value="">Estatus de estado</option>
                        <option {!! $activo == '0' ? 'selected' : '' !!} value="0">Activos</option>
                        <option {!! $activo == '1' ? 'selected' : '' !!} value="1">Inactivos</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Estado</th>
                        <th>Nombre</th>
                        <th></th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estados as $estado)
                        <tr>
                            <td>{{ $estado->IdEstado }}</td>
                            <td style="width: 60%">{{ $estado->NomEstado }}</td>
                            <td>
                                @if ($estado->Status)
                                    <span class="tags-red">
                                        @include('components.icons.x')
                                    </span>
                                @else
                                    <span class="tags-green">
                                        @include('components.icons.check-all')
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $estado->IdEstado }}">
                                    @include('components.icons.edit')
                                </button>
                            </td>
                        </tr>
                        <!--Modal Editar-->
                        @include('Estados.ModalEditar')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $estados])
        </div>
    </div>
    <!--Modal Agregar Estado-->
    @include('Estados.ModalAgregar')
@endsection
