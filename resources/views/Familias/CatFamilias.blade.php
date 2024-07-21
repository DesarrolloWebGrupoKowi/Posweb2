@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Familias')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Familias'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar familia @include('components.icons.plus-circle')
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
                        <th>Familia</th>
                        {{-- <th class="rounded-end">Acciones</th> --}}
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($familias) == 0)
                        <tr>
                            <td colspan="3">No Hay Familias!</td>
                        </tr>
                    @else
                        @foreach ($familias as $familia)
                            <tr>
                                <td>{{ $familia->IdFamilia }}</td>
                                <td>{{ $familia->NomFamilia }}</td>
                                <td>
                                    {{-- <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar-{{ $familia->IdFamilia }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                    <button class="btn btn-sm">
                                        <span class="material-icons eliminar">delete_forever</span>
                                    </button> --}}
                                </td>
                                <!-- Modal Editar -->
                                @include('Familias.ModalEditar')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @include('components.paginate', ['items' => $familias])
        </div>
    </div>
    @include('Familias.ModalAgregar')
@endsection
