@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Menu Tipo Usuario')
@section('dashboardWidth', 'width-95')
<style>
    * {
        scrollbar-width: thin;
        scrollbar-color: #797979 #f1f2f300;
    }

    .over {
        overflow: hidden;
        border-radius: 20px;
    }
</style>
@section('contenido')
    <div class="container-fluid pt-4 width-95">
        <form id="formChange" action="/DatMenuTipoUsuario">

            <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-5">
                @if (empty($TipoUsuarioFind))
                    @include('components.title', ['titulo' => 'Asignación de Menús'])
                @else
                    @include('components.title', [
                        'titulo' => 'Asignación de Menús Para ' . $TipoUsuarioFind->NomTipoUsuario,
                    ])
                @endif
                <div class="d-flex align-items-center justify-content-end">
                    <div class="form-group" style="min-width: 300px">
                        <label class="fw-bold text-secondary">Tipo de usuario</label>
                        <select class="form-select " name="IdTipoUsuario" id="IdTipoUsuario" onchange="MenuTipoUsuario()">
                            <option value="0">Seleccione</option>
                            @foreach ($tipoUsuarios as $tipoUsuario)
                                <option {!! $filtroIdTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' !!} value="{{ $tipoUsuario->IdTipoUsuario }}">
                                    {{ $tipoUsuario->NomTipoUsuario }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>

            <div class="row">

                <div class="col-6">
                    <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
                        <h5>Menús Agregados</h5>
                        <button class="btn btn-sm btn-dark" onclick="remove()">
                            <i class="fa fa-plus-circle pe-1"></i> Remover
                        </button>
                    </div>

                    <div class="over">
                        <div class="content-table content-table-full card p-4 table-responsive"
                            style="border-radius: 20px; height: 70vh">
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Menús ({{ count($menuTipoUsuarios) }})</th>
                                        <th class="rounded-end">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($menuTipoUsuarios) == 0)
                                        @if ($filtroIdTipoUsuario == 0)
                                            <tr>
                                                <td colspan="2">Seleccione Tipo de Usuario</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2">No Hay Menús para este Tipo de Usuario</td>
                                            </tr>
                                        @endif
                                    @else
                                        @foreach ($menuTipoUsuarios as $menuTipoUsuario)
                                            <tr>
                                                <td>{{ $menuTipoUsuario->cmpNomMenu }}</td>
                                                <td><input class="d-block form-check-input" type="checkbox"
                                                        name="chkRemoverMenu[]" id="chkRemoverMenu[]"
                                                        value="{{ $menuTipoUsuario->cmpIdMenu }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-6">
                    <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
                        <h5>Agregar Menús</h5>
                        <button class="btn btn-sm btn-dark" onclick="agregar()">
                            <i class="fa fa-plus-circle pe-1"></i> Agregar
                        </button>
                    </div>
                    <div class="over">
                        <div class="content-table content-table-full card p-4 table-responsive"
                            style="border-radius: 20px; height: 70vh">
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Menús ({{ count($menus) }})</th>
                                        <th class="rounded-end">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($menus == '' || $filtroIdTipoUsuario == 0)
                                        <tr>
                                            <td colspan="2">Seleccione Tipo de Usuario</td>
                                        </tr>
                                    @else
                                        @if (count($menus) == 0)
                                            <tr>
                                                <td colspan="2">No Hay Menús por Agregar</td>
                                            </tr>
                                        @endif
                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td>{{ $menu->NomMenu }}</td>
                                                <td>
                                                    <input class="d-block form-check-input" type="checkbox"
                                                        name="chkAgregarMenu[]" id="chkAgregarMenu[]"
                                                        value="{{ $menu->IdMenu }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
