@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Ciudades')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Ciudades'])
            <div class="">
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar ciudad
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatCiudades" method="get">
            <div class="input-group" style="max-width: 300px">
                <input class="form-control" type="text" name="txtFiltro" id="txtFiltro"
                    placeholder="Escribre el nombre de la ciudad" value="{{ $txtFiltro }}">
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
                        <th>Estado</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($ciudades) <= 0)
                        <td colspan="4">No Hay Coincidencias!</td>
                    @else
                        @foreach ($ciudades as $ciudad)
                            <tr>
                                <td>{{ $ciudad->IdCiudad }}</td>
                                <td>{{ $ciudad->NomCiudad }}</td>
                                <td>{{ $ciudad->NomEstado }}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $ciudad->IdCiudad }}"><span
                                            class="material-icons">edit</span></button>
                                </td>
                            </tr>
                            @include('Ciudades.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!--Modal Agregar Estado-->
    @include('Ciudades.ModalAgregar')
    <br>
    <div class="d-flex justify-content-center">
        {!! $ciudades->links() !!}
    </div>
@endsection
