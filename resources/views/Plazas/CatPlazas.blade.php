@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Plazas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Plazas'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar plaza @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/CatPlazas">
                <div>
                    <select class="form-select rounded" style="line-height: 18px" name="activo" id="activo">
                        <option value="">Estatus de plaza</option>
                        <option {!! $activo == '0' ? 'selected' : '' !!} value="0">Activas</option>
                        <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivas</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $plazas, 'colspan' => 5])
                    @foreach ($plazas as $plaza)
                        <tr>
                            <td>{{ $plaza->IdPlaza }}</td>
                            <td>{{ $plaza->NomPlaza }}</td>
                            <td>{{ $plaza->ccNomCiudad }}</td>
                            <td>{{ $plaza->ceNomEstado }}</td>
                            <td>
                                @if ($plaza->Status)
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
                                    data-bs-target="#ModalEditar{{ $plaza->IdPlaza }}">
                                    @include('components.icons.edit')
                                </button>
                            </td>
                        </tr>
                        <!--Modal Editar Plaza-->
                        @include('Plazas.ModalEditar')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--Modal Agregar Plaza-->
    @include('Plazas.ModalAgregar')
@endsection
