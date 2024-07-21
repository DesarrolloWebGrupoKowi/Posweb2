@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Menu Tipo Usuario')
@section('dashboardWidth', 'width-general')
<style>
    /* * {
        scrollbar-width: thin;
        scrollbar-color: #797979 #f1f2f300;
    } */

    /* .over {
        overflow: hidden;
        border-radius: 20px;
    } */
</style>
@section('contenido')
    <form id="formChange" action="/DatMenuTipoUsuario">
        <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">
            <div class="card border-0 p-4" style="border-radius: 10px">
                <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
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
                            <select class="form-select rounded" style="line-height: 18px" name="IdTipoUsuario"
                                id="IdTipoUsuario" onchange="MenuTipoUsuario()">
                                <option value="0">Seleccione tipo de usuario</option>
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
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="over">
                        <div class="content-table content-table-full card border-0 p-4"
                            style="border-radius: 10px; height: 70vh">
                            <h5>Menús Agregados</h5>
                            {{-- <div
                                class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
                                <button class="btn btn-sm btn-dark" onclick="remove()">
                                    <i class="fa fa-plus-circle pe-1"></i> Remover
                                </button>
                            </div> --}}
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Menús ({{ count($menuTipoUsuarios) }})</th>
                                        <th></th>
                                        <th class="rounded-end">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @include('components.table-empty', [
                                        'items' => $menuTipoUsuarios,
                                        'colspan' => 2,
                                    ]) --}}
                                    @foreach ($menuTipoUsuarios as $menuTipoUsuario)
                                        <tr style="font-size: small">
                                            <td>
                                                <a href="{{ $menuTipoUsuario->cmpLink }}" target="_blank"
                                                    class="cursor-pointer text-decoration-none" style="color: #4f5464">
                                                    {{ $menuTipoUsuario->cmpNomMenu }}
                                                </a>
                                            </td>
                                            <td>{{ $menuTipoUsuario->NomTipoMenu }}</td>
                                            <td><input class="d-block form-check-input" type="checkbox"
                                                    name="chkRemoverMenu[]" id="chkRemoverMenu[]"
                                                    value="{{ $menuTipoUsuario->cmpIdMenu }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pt-2 d-flex justify-content-end">
                                <button class="btn btn-sm btn-danger" onclick="remove()"
                                    {{ count($menuTipoUsuarios) == 0 ? 'disabled' : '' }}>
                                    Remover @include('components.icons.delete')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="over">
                        <div class="content-table content-table-full card border-0 p-4"
                            style="border-radius: 10px; height: 70vh">
                            <h5>Agregar Menús</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Menús ({{ count($menus) }})</th>
                                        <th></th>
                                        <th class="rounded-end">Seleccionar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @include('components.table-empty', [
                                        'items' => $menus,
                                        'colspan' => 2,
                                    ]) --}}
                                    @foreach ($menus as $menu)
                                        <tr style="font-size: small">
                                            <td>
                                                <a href="{{ $menu->Link }}" target="_blank" class="text-decoration-none"
                                                    style="color: #4f5464">
                                                    {{ $menu->NomMenu }}
                                            </td>
                                            </a>
                                            <td>{{ $menu->NomTipoMenu }}</td>
                                            <td>
                                                <input class="d-block form-check-input" type="checkbox"
                                                    name="chkAgregarMenu[]" id="chkAgregarMenu[]"
                                                    value="{{ $menu->IdMenu }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pt-2 d-flex justify-content-end">
                                <button class="btn btn-sm btn-warning" onclick="agregar()"
                                    {{ count($menus) == 0 ? 'disabled' : '' }}>
                                    Agregar @include('components.icons.plus-circle')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection
