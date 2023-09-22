@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Familias')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Familias'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar familia
                </button>
                <a href="/CatFamilias" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatFamilias">
            <div class="input-group" style="max-width: 300px">
                <input type="text" name="txtFiltro" class="form-control" placeholder="Busqueda"
                    value="{{ $txtFiltro }}">
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
        </div>
    </div>
    <div class="mt-5 d-flex justify-content-center">
        {!! $familias->links() !!}
    </div>
    @include('Familias.ModalAgregar')
@endsection
