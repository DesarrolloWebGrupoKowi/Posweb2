@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Ciudades')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Ciudades'])
                <div class="">
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar ciudad @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            @include('components.table-search')
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
                    @include('components.table-empty', ['items' => $ciudades, 'colspan' => 4])
                    @foreach ($ciudades as $ciudad)
                        <tr>
                            <td>{{ $ciudad->IdCiudad }}</td>
                            <td>{{ $ciudad->NomCiudad }}</td>
                            <td>{{ $ciudad->NomEstado }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $ciudad->IdCiudad }}">
                                    @include('components.icons.edit')
                                </button>
                            </td>
                        </tr>
                        @include('Ciudades.ModalEditar')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $ciudades])
        </div>
    </div>
    <!--Modal Agregar Estado-->
    @include('Ciudades.ModalAgregar')
@endsection
