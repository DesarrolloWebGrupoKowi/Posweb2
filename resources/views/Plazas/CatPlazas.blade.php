@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Plazas')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Plazas'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar plaza
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatPlazas">
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="activo" id="activo">
                    <option {!! $activo == 0 ? 'selected' : '' !!} value="0">Activas</option>
                    <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivas</option>
                </select>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($plazas) <= 0)
                        <td colspan="5">No Hay Ninguna Plaza!</td>
                    @else
                        @foreach ($plazas as $plaza)
                            <tr>
                                <td>{{ $plaza->IdPlaza }}</td>
                                <td>{{ $plaza->NomPlaza }}</td>
                                <td>{{ $plaza->ccNomCiudad }}</td>
                                <td>{{ $plaza->ceNomEstado }}</td>
                                <td>
                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $plaza->IdPlaza }}"><span
                                            class="material-icons">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!--Modal Editar Plaza-->
                            @include('Plazas.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!--Modal Agregar Plaza-->
    @include('Plazas.ModalAgregar')
@endsection
