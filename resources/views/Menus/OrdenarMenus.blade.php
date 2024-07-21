@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ordenar Menús por Tipo de Usuario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Ordenar Menús'])
                <form class="d-flex align-items-center justify-content-end" id="formTipoUsuario" action="/OrdenarMenus"
                    method="GET">
                    <div class="form-group" style="min-width: 300px">
                        <label class="fw-bold text-secondary">Tipo de usuario</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTipoUsuario"
                            id="idTipoUsuario">
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
                @include('Alertas.Alertas')
            </div>
        </div>
        <div>
            @if (!$menus->count() && !$idTipoUsuario)
                <h2 class="text-center"> Seleccione un tipo de usuario</h2>
            @endif
            @if (!$menus->count() && $idTipoUsuario)
                <h2 class="text-center"> Este usuario no cuenta con menus asignados</h2>
                <div class="d-flex justify-content-center mt-4">
                    <form id="formChange" action="/DatMenuTipoUsuario" target="_blank">
                        <input type="hidden" name="IdTipoUsuario" value="{{ $idTipoUsuario }}" />
                        <button class="btn btn-sm btn-dark" title="Asignar menus">
                            Asignar menú
                        </button>
                    </form>
                </div>
            @endif
            @foreach ($menus as $menu)
                <form action="/EditarPosicionMenu">
                    <input type="hidden" name="idTipoUsuario" value="{{ $idTipoUsuario }}">

                    <div class="content-table content-table-full card border-0 p-4 mb-4" style="border-radius: 10px">
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
                                        <td class="d-flex align-items-center">
                                            <input style="width: 15vh; line-height: 18px" class="form-control rounded"
                                                type="text" name="posicion[{{ $nMenu->PivotMenu->IdMenu }}]"
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
