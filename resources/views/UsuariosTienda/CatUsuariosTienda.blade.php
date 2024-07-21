@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Usuarios Tienda'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar usuario @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                @include('components.table-search')
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Usuario</th>
                        <th>Tienda</th>
                        <th>Plaza</th>
                        <th>Todas</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $usuariosTienda, 'colspan' => 6])
                    @foreach ($usuariosTienda as $usuarioTienda)
                        <tr>
                            <td>{{ $usuarioTienda->IdUsuarioTienda }}</td>
                            <td>{{ $usuarioTienda->NomUsuario }}</td>
                            @if (empty($usuarioTienda->NomTienda))
                                <td>-</td>
                            @else
                                <td>{{ $usuarioTienda->NomTienda }}</td>
                            @endif
                            @if (empty($usuarioTienda->NomPlaza))
                                <td>-</td>
                            @else
                                <td>{{ $usuarioTienda->NomPlaza }}</td>
                            @endif
                            @if ($usuarioTienda->Todas == 0)
                                <td>
                                    <span class="tags-green">
                                        @include('components.icons.check-all')
                                    </span>
                                </td>
                            @else
                                <td>
                                    <span class="tags-red">
                                        @include('components.icons.x')
                                    </span>
                                </td>
                            @endif
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}">
                                    @include('components.icons.edit')
                                </button>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                        </tr>
                        @include('UsuariosTienda.ModalEditar')
                        @include('UsuariosTienda.ModalEliminar')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $usuariosTienda])
        </div>
    </div>

    <!--Modal Agregar Usuario Tienda-->
    @include('UsuariosTienda.ModalAgregar')
@endsection
