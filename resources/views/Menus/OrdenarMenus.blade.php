@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ordenar Menús por Tipo de Usuario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Ordenar Menús'])
            <form class="d-flex align-items-center justify-content-end" id="formTipoUsuario" action="/OrdenarMenus"
                method="GET">
                <div class="form-group" style="min-width: 300px">
                    <label class="fw-bold text-secondary">Tipo de usuario</label>
                    <select class="form-select" name="idTipoUsuario" id="idTipoUsuario">
                        <option value="0">Seleccione un tipo de usuario</option>
                        @foreach ($tiposUsuario as $tipoUsuario)
                            <option {!! $idTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{ $tipoUsuario->IdTipoUsuario }}">
                                {{ $tipoUsuario->NomTipoUsuario }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div>
            @if ($menus->count() == 0 && !empty($idTipoUsuario))
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <h4 class="bg-danger text-white p-1 shadow"><i class="fa fa-exclamation-triangle"></i> No Hay Menús
                            <i class="fa fa-exclamation-triangle"></i>
                        </h4>
                    </div>
                </div>
            @else
                @foreach ($menus as $menu)
                    <form action="/EditarPosicionMenu">
                        <input type="hidden" name="idTipoUsuario" value="{{ $idTipoUsuario }}">

                        <div class="content-table content-table-full card p-4 mb-4" style="border-radius: 20px">
                            <h5 class="fw-bold pb-1" style="color: #374151">{{ $menu->NomTipoMenu }}</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Menú</th>
                                        <th class="rounded-end">Posición</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menu->Ordenar as $nMenu)
                                        <tr>
                                            <td>{{ $nMenu->PivotMenu->NomMenu }}</td>
                                            <td class="d-flex align-items-center"><input style="width: 15vh;"
                                                    class="form-control" type="text"
                                                    name="posicion[{{ $nMenu->PivotMenu->IdMenu }}]"
                                                    value="{{ $nMenu->Posicion }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-warning shadow">
                                    <i class="fa fa-save"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                @endforeach
            @endif
        </div>
    </div>

    <script>
        const idTipoUsuario = document.getElementById('idTipoUsuario');
        const formTipoUsuario = document.getElementById('formTipoUsuario');
        idTipoUsuario.addEventListener('change', (e) => {
            formTipoUsuario.submit();
        });
    </script>
@endsection
