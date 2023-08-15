@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Estados')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Estados'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar Estado
                </button>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatEstados" method="get">
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="Activo" id="Activo">
                    <option {!! $activo == '' ? 'selected' : '' !!} value="0">Activos</option>
                    <option {!! $activo == '1' ? 'selected' : '' !!} value="1">Inactivos</option>
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
                        <th class="rounded-start">Id Estado</th>
                        <th>Nombre</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estados as $estado)
                        <tr>
                            <td>{{ $estado->IdEstado }}</td>
                            <td style="width: 80%">{{ $estado->NomEstado }}</td>
                            <td>
                                <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $estado->IdEstado }}">
                                    <span class="material-icons">edit</span>
                                </button>
                            </td>
                        </tr>
                        <!--Modal Editar-->
                        @include('Estados.ModalEditar')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center">
        {!! $estados->links() !!}
    </div>
    <!--Modal Agregar Estado-->
    @include('Estados.ModalAgregar')
@endsection
